<?php

namespace App\Livewire\LoginEmpleado;

use App\Models\Asistencia;
use App\Models\AsistenciaNomina;
use App\Models\Empleado;
use App\Models\PeriodoNomina;
use App\Models\RegistroNomina;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LoginEmpleado extends Component
{
    // Providers/FortifyServiceProvider.php
    // app/Actions/Fortify/LoginResponse.php
    // resources/views/components/layouts/empleado.blade.php
    // routes/web.php: Route::get('login-empleado', LoginEmpleado::class)->name('login-empleado');

    public $empleado_id;

    public function mount()
    {
        $user = Auth::user();
        $empleado = Empleado::where('user_id', $user->id)->first();

        if (!$empleado) {
            abort(403, 'No eres un empleado registrado.');
        }

        $this->empleado_id = $empleado->id;
        $this->marcarEntradaSalida();
    }

    public function marcarEntradaSalida()
    {
        $hoy = now()->format('Y-m-d');

        $asistencia = Asistencia::firstOrCreate(
            [
                'empleado_id' => $this->empleado_id,
                'fecha'       => $hoy,
            ]
        );

        $ahora = now();
        $mensaje = ''; // Variable para almacenar el mensaje a mostrar

        // Registrar entrada normal
        if (!$asistencia->entrada) {
            $asistencia->entrada = $ahora;
            $mensaje = 'Entrada registrada';
        } 
        // Registrar salida normal
        elseif (!$asistencia->salida) {
            $asistencia->salida = $ahora;

            $entrada = \Carbon\Carbon::parse($asistencia->entrada);
            $salida = \Carbon\Carbon::parse($asistencia->salida);

            // Calcular horas trabajadas completas (floor ignora minutos)
            $horasTrabajadas = floor($salida->diffInMinutes($entrada) / 60);

            // Jornada normal = 8 horas
            $asistencia->horas_extra = max(0, $horasTrabajadas - 8);

            // $mensaje = "Salida registrada con $horasTrabajadas hrs, horas extra: {$asistencia->horas_extra}";
            $mensaje = "Salida registrada";

            $this->registrarEnNomina($asistencia);
        } 
        // Registrar horas extra
        else {
            if (!$asistencia->entrada_extra) {
                $asistencia->entrada_extra = $ahora;
                $mensaje = 'Entrada extra registrada';
            } elseif (!$asistencia->salida_extra) {
                $asistencia->salida_extra = $ahora;

                $entradaExtra = Carbon::parse($asistencia->entrada_extra);
                $salidaExtra = Carbon::parse($asistencia->salida_extra);

                $horasExtra = floor(abs($salidaExtra->diffInMinutes($entradaExtra, false)) / 60);

                $asistencia->horas_extra += $horasExtra;

                $mensaje = "Salida extra registrada. Horas extra totales: {$asistencia->horas_extra}";

                $this->registrarEnNomina($asistencia);
            } else {
                $mensaje = 'Ya registraste todo hoy';
            }
        }

        $asistencia->save();

        // Cerrar sesión
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        // Redirigir a la vista Blade de confirmación con el mensaje
        return redirect()->route('empleado.confirmacion')->with('mensaje', $mensaje);
    }

    /* ========================================================
     *  REGISTRAR ASISTENCIA EN NÓMINA
     * ====================================================== */
    public function registrarEnNomina(Asistencia $asistencia)
    {
        // 1. Detectar a qué periodo pertenece
        $periodo = PeriodoNomina::whereDate('fecha_inicio', '<=', $asistencia->fecha)
            ->whereDate('fecha_fin', '>=', $asistencia->fecha)
            ->first();

        if (!$periodo) {
            return; // No pertenece a un periodo
        }

        // 2. Obtener/crear registro de nómina
        $registroNomina = RegistroNomina::firstOrCreate([
            'empleado_id'       => $asistencia->empleado_id,
            'periodo_nomina_id' => $periodo->id,
        ]);

        // 3. Obtener/crear asistencias_nomina
        $asistNomina = AsistenciaNomina::firstOrCreate([
            'registro_nomina_id' => $registroNomina->id,
        ]);

        // 4. Determinar día
        $mapa = [
            'monday'    => 'lunes',
            'tuesday'   => 'martes',
            'wednesday' => 'miercoles',
            'thursday'  => 'jueves',
            'friday'    => 'viernes',
            'saturday'  => 'sabado',
            'sunday'    => 'domingo',
        ];

        $diaIngles = strtolower($asistencia->fecha->format('l'));
        $diaCampo  = $mapa[$diaIngles];

        // 5. Registrar asistencia del día
        $asistNomina->$diaCampo = true;

        // 6. Registrar horas extra del día
        $extraCampo = "horas_extra_" . $diaCampo;
        $asistNomina->$extraCampo = $asistencia->horas_extra;

        $asistNomina->save();

        // 7. Actualizar total del periodo
        $registroNomina->total_horas_extras = 
            $asistNomina->horas_extra_lunes +
            $asistNomina->horas_extra_martes +
            $asistNomina->horas_extra_miercoles +
            $asistNomina->horas_extra_jueves +
            $asistNomina->horas_extra_viernes +
            $asistNomina->horas_extra_sabado +
            $asistNomina->horas_extra_domingo;

        $registroNomina->save();
        $registroNomina->calcularNomina();
    }

    public function render()
    {
        return view('livewire.login-empleado.login-empleado')
            ->layout('components.layouts.empleado');
    }
}