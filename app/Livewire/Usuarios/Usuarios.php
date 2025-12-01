<?php

namespace App\Livewire\Usuarios;

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
    public $name, $email, $password, $role; 
    public $roles = [];
    public $deleteId = null;

    public $turno = '';
    public $ubicacion = '';
    public $adscrito = '';

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

    // public function cancelDelete()
    // {
    //     $this->deleteId = null;
    //     Flux::modal('confirm-delete')->close();
    // }

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
                'email' => $this->email,
            ]);
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
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
        $this->email = $user->email;
        $this->role = $user->getRoleNames()->first();

        Flux::modal('nuevo-usuario')->show();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
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
