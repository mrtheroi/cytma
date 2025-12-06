<?php

use App\Livewire\Asistencias\Asistencias;
use App\Livewire\LoginEmpleado\LoginEmpleado;
use App\Livewire\Percepciones\Percepciones;
use App\Livewire\Periodos\Periodos;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use App\Livewire\Usuarios\Usuarios;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified', 'role:administrador|super_administrador'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('usuarios', Usuarios::class)->name('usuarios');
    Route::get('periodos', Periodos::class)->name('periodos');
    Route::get('asistencias', Asistencias::class)->name('asistencias');
    Route::get('percepciones', Percepciones::class)->name('percepciones');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

Route::get('login-empleado', LoginEmpleado::class)->name('login-empleado');

// Rutas solo para administradores y super administradores
// Route::middleware(['auth', 'role:administrador|super_administrador'])->group(function () {

//     Route::get('usuarios', Usuarios::class)->name('usuarios');
//     Route::get('periodos', Periodos::class)->name('periodos');
//     Route::get('asistencias', Asistencias::class)->name('asistencias');
//     Route::get('percepciones', Percepciones::class)->name('percepciones');

// });
