<?php

namespace App\Livewire\Usuarios;

use App\Models\ConceptoNomina;
use App\Models\DetalleNomina;
use App\Models\Empleado;
use App\Models\PeriodoNomina;
use App\Models\RegistroNomina;
use Livewire\Component;
use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Usuarios extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search = '';
    public $editingId = null;
    public $name, $first_name, $last_name, $email, $password, $role; 
    public $roles = [];
    public $deleteId = null;

    public $turno = '';
    public $ubicacion = '';
    public $adscrito = '';
    public $categoria = '';
    public $costo_dia = 0;
    public $costo_hora_extra = 0;
    public $sueldo_pactado = 0;

    public $detalleAnticipoId = null;  // si es edición
    public $registroNominaId = null;   // el registro al que pertenece el anticipo
    public $montoAnticipo = 0;

    public function mount()
    {
        $this->roles = Role::all();
    }

    protected function applySearch($query)
    {
        return $this->search === ''
            ? $query
            : $query
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
    }

    public function newUser()
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->resetValidation();

        Flux::modal('nuevo-usuario')->show();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        Flux::modal('confirm-delete')->show();
    }

    public function guardarAnticipo()
    {
        $this->validate([
            'montoAnticipo' => 'required|numeric|min:0'
        ]);

        $registro = RegistroNomina::find($this->registroNominaId);

        if (!$registro) {
            throw new \Exception("Registro de nómina no encontrado.");
        }

        // Buscar si ya existe
        $detalle = DetalleNomina::find($this->detalleAnticipoId);

        $conceptoAnticipo = ConceptoNomina::where('nombre', 'anticipo')->first();

        // MODO EDITAR
        if ($detalle) {

            // Restar monto anterior
            $registro->deducciones_totales -= $detalle->monto;

            // Actualizar detalle
            $detalle->monto = $this->montoAnticipo;
            $detalle->save();

        } else {
            // MODO CREAR
            $detalle = DetalleNomina::create([
                'registro_nomina_id' => $this->registroNominaId,
                'concepto_nomina_id' => $conceptoAnticipo->id,
                'monto' => $this->montoAnticipo,
            ]);
        }

        // Sumar monto nuevo
        $registro->deducciones_totales += $this->montoAnticipo;

        $registro->save();
        $registro->calcularNomina();
        Flux::modal('modal-anticipo')->close();

        LivewireAlert::title('¡Éxito!')
            ->text('El anticipo fue guardado correctamente.')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }
    public function abrirAnticipo($userId)
    {
        // Obtener al empleado por user_id
        $empleado = Empleado::where('user_id', $userId)->first();

        if (!$empleado) {
            // return $this->errorAnticipo("El usuario no tiene perfil de empleado.");
            LivewireAlert::title('Error')
                ->text('Ups! El usuario no tiene perfil de empleado.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        // Buscar periodo activo
        $periodo = PeriodoNomina::whereDate('fecha_inicio', '<=', today())
            ->whereDate('fecha_fin', '>=', today())
            ->first();

        if (!$periodo) {
            return $this->errorAnticipo("No existe periodo de nómina activo.");
            LivewireAlert::title('Error')
                ->text('Ups! No existe periodo de nómina activo.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        // Buscar registro de nómina del empleado
        $registro = RegistroNomina::where('empleado_id', $empleado->id)
            ->where('periodo_nomina_id', $periodo->id)
            ->first();

        if (!$registro) {
            // return $this->errorAnticipo("El empleado no está registrado en el periodo actual.");
            LivewireAlert::title('Error')
                ->text('Ups! El empleado no está registrado en un periodo activo. No se puede asignar un anticipo.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        // Guardamos el ID del registro para usarlo en guardarAnticipo()
        $this->registroNominaId = $registro->id;

        // Buscar detalle del anticipo
        $detalleAnticipo = DetalleNomina::where('registro_nomina_id', $registro->id)
            ->whereHas('concepto', fn ($q) => $q->where('nombre', 'anticipo'))
            ->first();

        if ($detalleAnticipo) {
            $this->detalleAnticipoId = $detalleAnticipo->id;
            $this->montoAnticipo = $detalleAnticipo->monto;
        } else {
            $this->detalleAnticipoId = null;
            $this->montoAnticipo = 0;

            Flux::modal('modal-anticipo')->close();
            LivewireAlert::title('Error')
                ->text('Ups! El empleado no está registrado en un periodo activo. No se puede asignar un anticipo.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
        }

        Flux::modal('modal-anticipo')->show();
    }

    public function deleteUser()
    {
        $user = User::findOrFail($this->deleteId);

        $user->delete();

        $this->deleteId = null;

        Flux::modal('confirm-delete')->close();

        LivewireAlert::title('Usuario eliminado')
            ->text('El usuario fue eliminado correctamente.')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }
    
    public function save()
    {
        $isEditing = $this->editingId ? true : false;

        $rules = [
            'name' => 'required|string|max:255',
            'role' => 'required|exists:roles,name',
        ];

        if (!$this->editingId) {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|min:8';
        } else {
            $rules['email'] = 'required|email|unique:users,email,' . $this->editingId;
        }

        $this->validate($rules);

        if ($isEditing) {
            $user = User::findOrFail($this->editingId);
            $user->update([
                'name' => $this->name,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
            ]);
            // Actualizar datos del empleado si existe
            $empleado = Empleado::where('user_id', $user->id)->first();

            if ($empleado) {
                $empleado->update([
                    'nombre' => $this->name,
                    'apellido_paterno' => $this->first_name,
                    'apellido_materno' => $this->last_name,
                    'sueldo_pactado' => $this->sueldo_pactado,
                    'turno' => $this->turno,
                    'categoria' => $this->categoria,
                    'unidad_negocio' => $this->ubicacion,
                    'adscrito' => $this->adscrito,
                    'costo_dia' => $this->costo_dia,
                    'costo_hora' => $this->costo_dia / 8,
                    'costo_hora_extra' => $this->costo_hora_extra,
                ]);
            }
        } else {
            $user = User::create([
                'name' => $this->name,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            $hora_costo = $this->costo_dia / 8;

            Empleado::create([
                'user_id' => $user->id,
                'nombre' => $this->name,
                'apellido_paterno' => $this->first_name,
                'apellido_materno' => $this->last_name,
                'sueldo_pactado' => $this->sueldo_pactado,

                'turno' => $this->turno,
                'categoria' => $this->categoria,
                'unidad_negocio' => $this->ubicacion,
                'adscrito' => $this->adscrito,
                'costo_dia' => $this->costo_dia,
                'costo_hora' => $hora_costo,
                'costo_hora_extra' => $this->costo_hora_extra,
            ]);
        }

        $user->syncRoles([$this->role]);

        $this->resetForm();
        Flux::modal('nuevo-usuario')->close();

        $message = $isEditing
            ? 'Usuario actualizado correctamente'
            : 'Usuario creado correctamente';

        LivewireAlert::title('¡Éxito!')
            ->text($message)
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function edit($userId)
    {
        $user = User::findOrFail($userId);

        $this->editingId = $userId;
        $this->name = $user->name;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->role = $user->getRoleNames()->first() ?? '';

        $empleado = Empleado::where('user_id', $userId)->first();

        if ($empleado) {
            $this->turno = $empleado->turno;
            $this->ubicacion = $empleado->unidad_negocio;
            $this->adscrito = $empleado->adscrito;
            $this->categoria = $empleado->categoria;
            $this->costo_dia = $empleado->costo_dia;
            $this->costo_hora_extra = $empleado->costo_hora_extra;
            $this->sueldo_pactado = $empleado->sueldo_pactado;
        }

        Flux::modal('nuevo-usuario')->show();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->first_name = '';
        $this->last_name = '';
        $this->email = '';
        $this->password = '';
        $this->role = '';
    }

    public function render()
    {
        $query = User::query();
        $query = $this->applySearch($query);

        $users = $query->paginate(10);
        
        return view('livewire.usuarios.usuarios', compact('users'));
    }
}
