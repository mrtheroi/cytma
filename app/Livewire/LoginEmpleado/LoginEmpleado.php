<?php

namespace App\Livewire\LoginEmpleado;

use App\Models\Asistencia;
use App\Models\Empleado;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

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

    // public function marcarEntradaSalida()
    // {
    //     $hoy = now()->toDateString();

    //     $asistencia = Asistencia::firstOrNew([
    //         'empleado_id' => $this->empleado_id,
    //         'fecha' => $hoy,
    //     ]);

    //     if (!$asistencia->entrada) {
    //         $asistencia->entrada = now();
    //         session()->flash('status', 'Entrada registrada');
    //     } elseif (!$asistencia->salida) {
    //         $asistencia->salida = now();

    //         // Calcular horas trabajadas
    //         $entrada = $asistencia->entrada;
    //         $salida = $asistencia->salida;

    //         $horasTrabajadas = $salida->diffInMinutes($entrada) / 60;

    //         // Supongamos que jornada normal son 8 horas
    //         $asistencia->horas_extra = max(0, $horasTrabajadas - 8);
    //         session()->flash('status', 'Salida registrada con ' . round($horasTrabajadas, 2) . ' hrs, horas extra: ' . $asistencia->horas_extra);
    //         LivewireAlert::title('¡Éxito!')
    //             ->text('El anticipo fue guardado correctamente.')
    //             ->success()
    //             ->toast()
    //             ->position('top-end')
    //             ->show();
    //     } else {
    //         session()->flash('status', 'Ya registraste entrada y salida hoy');
    //         LivewireAlert::title('¡Éxito!')
    //             ->text('El anticipo fue guardado correctamente.')
    //             ->success()
    //             ->toast()
    //             ->position('top-end')
    //             ->show();
    //     }

    //     $asistencia->save();

    //     // **Cerrar sesión después de registrar**
    //     Auth::logout();
    //     session()->invalidate();
    //     session()->regenerateToken();
  
    //     // Redirigir al login de Laravel
    //     return redirect()->route('login'); // ruta por defecto de Fortify/Laravel
    // }

    public function marcarEntradaSalida()
    {
        $hoy = now()->toDateString();

        $asistencia = Asistencia::firstOrNew([
            'empleado_id' => $this->empleado_id,
            'fecha' => $hoy,
        ]);

        $ahora = now();

        // Registrar entrada normal
        if (!$asistencia->entrada) {
            $asistencia->entrada = $ahora;
            session()->flash('status', 'Entrada registrada');
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

            session()->flash('status', 'Salida registrada con ' . $horasTrabajadas . ' hrs, horas extra: ' . $asistencia->horas_extra);
        } 
        // Registrar horas extra
        else {
            if (!$asistencia->entrada_extra) {
                $asistencia->entrada_extra = $ahora;
                session()->flash('status', 'Entrada extra registrada');
            } elseif (!$asistencia->salida_extra) {
                $asistencia->salida_extra = $ahora;

                $entradaExtra = \Carbon\Carbon::parse($asistencia->entrada_extra);
                $salidaExtra = \Carbon\Carbon::parse($asistencia->salida_extra);

                $horasExtra = floor($salidaExtra->diffInMinutes($entradaExtra) / 60);
                $asistencia->horas_extra += $horasExtra;

                session()->flash('status', 'Salida extra registrada. Horas extra totales: ' . $asistencia->horas_extra);
            } else {
                session()->flash('status', 'Ya registraste todo hoy');
            }
        }

        $asistencia->save();

        // Cerrar sesión y redirigir
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.login-empleado.login-empleado')
            ->layout('components.layouts.empleado');
    }
}