<?php

namespace App\Livewire\LoginEmpleado;

use Livewire\Component;

class LoginEmpleado extends Component
{
    // public function render()
    // {
    //     return view('livewire.login-empleado.login-empleado');
    // }
    
    // public function render()
    // {
    //     // return view('livewire.login-empleado.login-empleado')->layout('layouts.empleado');
    //     return view('livewire.login-empleado.login-empleado')->layout('layouts.empleado');
    // }
    public function render()
    {
        return view('livewire.login-empleado.login-empleado')
            ->layout('components.layouts.empleado');
    }
}
