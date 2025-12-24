<?php

use App\Livewire\Asistencias\Asistencias;
use App\Livewire\CustomerController;
use App\Livewire\DashboardController;
use App\Livewire\DieselLoadsController;
use App\Livewire\EquipmentController;
use App\Livewire\LoginEmpleado\LoginEmpleado;
use App\Livewire\Percepciones\Percepciones;
use App\Livewire\Periodos\Periodos;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use App\Livewire\SubrasanteDeliveryController;
use App\Livewire\SupplierController;
use App\Livewire\BusinessUnitController;
use App\Livewire\TrucksController;
use App\Livewire\Usuarios\Usuarios;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('livewire.auth.login');
})->name('home');

Route::get('version', function () {
    return response()->json([
        'version' => config('app.version'),
    ]);
});

//Route::view('dashboard', 'dashboard')
//    ->middleware(['auth', 'verified'])
//    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('usuarios', Usuarios::class)->name('usuarios');
    Route::get('periodos', Periodos::class)->name('periodos');
    Route::get('asistencias', Asistencias::class)->name('asistencias');
    Route::get('percepciones', Percepciones::class)->name('percepciones');
    Route::get('unidad-negocio', BusinessUnitController::class)->name('unidad');
    Route::get('suppliers', SupplierController::class)->name('suppliers');
    Route::get('equipment', EquipmentController::class)->name('equipment');
    Route::get('diesel-loads', DieselLoadsController::class)->name('diesel-loads');
    Route::get('trucks', TrucksController::class)->name('trucks');
    Route::get('customer', CustomerController::class)->name('customer');
    Route::get('delivery', SubrasanteDeliveryController::class)->name('delivery');
    Route::get('Dashboard', DashboardController::class)->name('dashboard');

    Route::get('login-empleado', LoginEmpleado::class)->name('login-empleado');

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

Route::get('/empleado/confirmacion', function () {
    return view('empleado.confirmacion');
})->name('empleado.confirmacion');

//Route::get('login-empleado', LoginEmpleado::class)->name('login-empleado');
