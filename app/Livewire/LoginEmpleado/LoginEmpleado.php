<?php

namespace App\Livewire\LoginEmpleado;

use App\Models\Asistencia;
use App\Models\Empleado;
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

    public function render()
    {
        return view('livewire.login-empleado.login-empleado')
            ->layout('components.layouts.empleado');
    }
}