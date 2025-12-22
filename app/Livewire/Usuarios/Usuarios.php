<?php

namespace App\Livewire\Usuarios;

use App\Livewire\ConfirmModal;
use App\Models\AsistenciaNomina;
use App\Models\ConceptoNomina;
use App\Models\DetalleNomina;
use App\Models\Empleado;
use App\Models\PeriodoNomina;
use App\Models\RegistroNomina;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Spatie\Permission\Models\Role;

class Usuarios extends Component
{
    use WithPagination, WithoutUrlPagination;

    // Search
    public string $search = '';

    // Modals (homologado)
    public bool $open = false;          // create/edit user modal
    public bool $openAnticipo = false;  // anticipo modal

    // Form state
    public ?int $editingId = null;

    public string $name = '';
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $password = '';
    public string $role = '';

    /** @var \Illuminate\Support\Collection<int, \Spatie\Permission\Models\Role> */
    public $roles;

    // Employee fields
    public string $turno = '';
    public string $ubicacion = '';
    public string $adscrito = '';
    public string $categoria = '';
    public float $costo_dia = 0;
    public float $costo_hora_extra = 0;
    public float $sueldo_pactado = 0;

    // Anticipo
    public ?int $detalleAnticipoId = null;  // editing detail id
    public ?int $registroNominaId = null;   // payroll record id
    public float $montoAnticipo = 0;

    public function mount(): void
    {
        $this->roles = Role::orderBy('name')->get();
    }

    // Reset pagination on search change
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    protected function applySearch($query)
    {
        return $this->search === ''
            ? $query
            : $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
    }

    // ----------------------------
    // Modals
    // ----------------------------
    public function newUser(): void
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->resetValidation();

        $this->open = true;
    }

    public function closeModal(): void
    {
        $this->open = false;
        $this->resetValidation();
    }

    public function closeAnticipoModal(): void
    {
        $this->openAnticipo = false;
        $this->resetValidation();
    }

    // ----------------------------
    // Delete (ConfirmModal pattern)
    // ----------------------------
    public function deleteConfirmation(int $id): void
    {
        $this->dispatch('showConfirmationModal', userId: $id)->to(ConfirmModal::class);
    }

    #[On('deleteConfirmed')]
    public function destroy(int $id): void
    {
        $user = User::findOrFail($id);
        $user->delete();

        $this->dispatch('notify', message: 'El usuario se eliminó con éxito', type: 'success');

        $this->resetPage();
    }

    // ----------------------------
    // Save user
    // ----------------------------
    public function save(): void
    {
        $isEditing = (bool) $this->editingId;

        $rules = [
            'name' => 'required|string|max:255',
            'role' => 'required|exists:roles,name',
            'first_name' => 'nullable|string|max:255',
            'last_name'  => 'nullable|string|max:255',
            'turno' => 'nullable|string|max:100',
            'ubicacion' => 'nullable|string|max:150',
            'adscrito' => 'nullable|string|max:150',
            'categoria' => 'nullable|string|max:150',
            'sueldo_pactado' => 'nullable|numeric|min:0',
            'costo_hora_extra' => 'nullable|numeric|min:0',
        ];

        if (!$isEditing) {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|string|min:8';
        } else {
            $rules['email'] = 'required|email|unique:users,email,' . $this->editingId;
            // password optional on edit (not used in your original controller)
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

            // Update employee profile if exists
            $empleado = Empleado::where('user_id', $user->id)->first();
            if ($empleado) {
                $costoDia = $this->sueldo_pactado > 0 ? round($this->sueldo_pactado / 7, 4) : 0;
                $costoHora = $costoDia > 0 ? round($costoDia / 8, 4) : 0;

                $empleado->update([
                    'nombre' => $this->name,
                    'apellido_paterno' => $this->first_name,
                    'apellido_materno' => $this->last_name,
                    'sueldo_pactado' => (float) $this->sueldo_pactado,
                    'turno' => $this->turno,
                    'categoria' => $this->categoria,
                    'unidad_negocio' => $this->ubicacion,
                    'adscrito' => $this->adscrito,
                    'costo_dia' => $costoDia,
                    'costo_hora' => $costoHora,
                    'costo_hora_extra' => round((float) $this->costo_hora_extra, 4),
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

            $this->costo_dia = $this->sueldo_pactado > 0 ? round($this->sueldo_pactado / 7, 4) : 0;
            $costoHora = $this->costo_dia > 0 ? round($this->costo_dia / 8, 4) : 0;

            $empleado = Empleado::create([
                'user_id' => $user->id,
                'nombre' => $this->name,
                'apellido_paterno' => $this->first_name,
                'apellido_materno' => $this->last_name,
                'sueldo_pactado' => (float) $this->sueldo_pactado,
                'turno' => $this->turno,
                'categoria' => $this->categoria,
                'unidad_negocio' => $this->ubicacion,
                'adscrito' => $this->adscrito,
                'costo_dia' => $this->costo_dia,
                'costo_hora' => $costoHora,
                'costo_hora_extra' => round((float) $this->costo_hora_extra, 4),
            ]);

            // Auto-add to active payroll period (original logic)
            $periodo = PeriodoNomina::whereDate('fecha_inicio', '<=', today())
                ->whereDate('fecha_fin', '>=', today())
                ->where('unidad_negocio', $empleado->unidad_negocio)
                ->first();

            if ($periodo) {
                $registroNomina = RegistroNomina::create([
                    'periodo_nomina_id' => $periodo->id,
                    'empleado_id' => $empleado->id,
                    'sueldo_pactado' => $empleado->sueldo_pactado,
                ]);

                // Initial anticipo (keep your original concepto id = 4)
                DetalleNomina::create([
                    'registro_nomina_id' => $registroNomina->id,
                    'concepto_nomina_id' => 4,
                    'monto' => 0,
                ]);

                AsistenciaNomina::create([
                    'registro_nomina_id' => $registroNomina->id,
                ]);
            }
        }

        // Roles
        $user->syncRoles([$this->role]);

        $this->dispatch(
            'notify',
            message: $isEditing ? 'Usuario actualizado correctamente' : 'Usuario creado correctamente',
            type: 'success'
        );

        $this->open = false;
        $this->resetForm();
        $this->resetPage();
    }

    // ----------------------------
    // Edit user
    // ----------------------------
    public function edit(int $userId): void
    {
        $user = User::findOrFail($userId);

        $this->editingId = $userId;
        $this->name = (string) $user->name;
        $this->first_name = (string) ($user->first_name ?? '');
        $this->last_name = (string) ($user->last_name ?? '');
        $this->email = (string) $user->email;
        $this->role = (string) ($user->getRoleNames()->first() ?? '');

        $empleado = Empleado::where('user_id', $userId)->first();
        if ($empleado) {
            $this->turno = (string) ($empleado->turno ?? '');
            $this->ubicacion = (string) ($empleado->unidad_negocio ?? '');
            $this->adscrito = (string) ($empleado->adscrito ?? '');
            $this->categoria = (string) ($empleado->categoria ?? '');
            $this->costo_dia = (float) ($empleado->costo_dia ?? 0);
            $this->costo_hora_extra = (float) ($empleado->costo_hora_extra ?? 0);
            $this->sueldo_pactado = (float) ($empleado->sueldo_pactado ?? 0);
        }

        $this->resetErrorBag();
        $this->resetValidation();

        $this->open = true;
    }

    // ----------------------------
    // Anticipo
    // ----------------------------
    public function abrirAnticipo(int $userId): void
    {
        $empleado = Empleado::where('user_id', $userId)->first();

        if (!$empleado) {
            $this->dispatch('notify', message: 'Ups! El usuario no tiene perfil de empleado.', type: 'error');
            return;
        }

        $periodo = PeriodoNomina::whereDate('fecha_inicio', '<=', today())
            ->whereDate('fecha_fin', '>=', today())
            ->first();

        if (!$periodo) {
            $this->dispatch('notify', message: 'Ups! No existe periodo de nómina activo.', type: 'error');
            return;
        }

        $registro = RegistroNomina::where('empleado_id', $empleado->id)
            ->where('periodo_nomina_id', $periodo->id)
            ->first();

        if (!$registro) {
            $this->dispatch('notify', message: 'Ups! El empleado no está registrado en un periodo activo. No se puede asignar un anticipo.', type: 'error');
            return;
        }

        $this->registroNominaId = $registro->id;

        $detalleAnticipo = DetalleNomina::where('registro_nomina_id', $registro->id)
            ->whereHas('concepto', fn ($q) => $q->where('nombre', 'anticipo'))
            ->first();

        if ($detalleAnticipo) {
            $this->detalleAnticipoId = $detalleAnticipo->id;
            $this->montoAnticipo = (float) $detalleAnticipo->monto;
        } else {
            // If anticipo detail doesn't exist, create it for this registro
            $conceptoAnticipo = ConceptoNomina::where('nombre', 'anticipo')->first();

            if (!$conceptoAnticipo) {
                $this->dispatch('notify', message: 'No existe el concepto "anticipo".', type: 'error');
                return;
            }

            $nuevo = DetalleNomina::create([
                'registro_nomina_id' => $registro->id,
                'concepto_nomina_id' => $conceptoAnticipo->id,
                'monto' => 0,
            ]);

            $this->detalleAnticipoId = $nuevo->id;
            $this->montoAnticipo = 0;
        }

        $this->resetErrorBag();
        $this->resetValidation();

        $this->openAnticipo = true;
    }

    public function guardarAnticipo(): void
    {
        $this->validate([
            'montoAnticipo' => 'required|numeric|min:0',
            'registroNominaId' => 'required|integer',
        ]);

        $registro = RegistroNomina::find($this->registroNominaId);
        if (!$registro) {
            $this->dispatch('notify', message: 'Registro de nómina no encontrado.', type: 'error');
            return;
        }

        $conceptoAnticipo = ConceptoNomina::where('nombre', 'anticipo')->first();
        if (!$conceptoAnticipo) {
            $this->dispatch('notify', message: 'No existe el concepto "anticipo".', type: 'error');
            return;
        }

        $detalle = $this->detalleAnticipoId ? DetalleNomina::find($this->detalleAnticipoId) : null;

        if ($detalle) {
            // subtract old
            $registro->deducciones_totales -= (float) $detalle->monto;

            // update detail
            $detalle->monto = (float) $this->montoAnticipo;
            $detalle->save();
        } else {
            $detalle = DetalleNomina::create([
                'registro_nomina_id' => $this->registroNominaId,
                'concepto_nomina_id' => $conceptoAnticipo->id,
                'monto' => (float) $this->montoAnticipo,
            ]);

            $this->detalleAnticipoId = $detalle->id;
        }

        // add new
        $registro->deducciones_totales += (float) $this->montoAnticipo;
        $registro->save();

        // keep your original behavior
        if (method_exists($registro, 'calcularNomina')) {
            $registro->calcularNomina();
        }

        $this->dispatch('notify', message: 'El anticipo fue guardado correctamente.', type: 'success');

        $this->openAnticipo = false;
    }

    // ----------------------------
    // Helpers
    // ----------------------------
    public function resetForm(): void
    {
        $this->editingId = null;

        $this->name = '';
        $this->first_name = '';
        $this->last_name = '';
        $this->email = '';
        $this->password = '';
        $this->role = '';

        $this->turno = '';
        $this->ubicacion = '';
        $this->adscrito = '';
        $this->categoria = '';
        $this->costo_dia = 0;
        $this->costo_hora_extra = 0;
        $this->sueldo_pactado = 0;
    }

    public function render()
    {
        $query = User::query();
        $query = $this->applySearch($query);

        $users = $query->orderBy('name')->paginate(10);

        return view('livewire.usuarios.usuarios', compact('users'));
    }
}
