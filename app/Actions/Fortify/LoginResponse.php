<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        // Si es trabajador → cerrar sesión y redirigir
        if ($user->hasRole('trabajador')) {
            // auth()->logout(); // cierra la sesión de Laravel
            // $request->session()->invalidate();
            // $request->session()->regenerateToken();

            return redirect()->route('login-empleado'); // ruta pública
        }

        // Admin / superadmin → dashboard
        return redirect()->intended('/dashboard');
    }
}