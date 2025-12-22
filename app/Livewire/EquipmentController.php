<?php

namespace App\Livewire;

use App\Models\Equipment;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class EquipmentController extends Component
{
    use WithPagination;

    // Para buscar
    #[Url]
    public $search = '';

    // Control para el modal
    public bool $open = false;

    public ?int $equipmentId = null;

    #[Rule('required|string|max:150')]
    public string $name = '';

    #[Rule('nullable|email|max:150')]
    public ?string $type = '';

    #[Rule('nullable|string|max:10')]
    public ?string $serial_number = '';



    // Para confirmar eliminación
    public ?int $deleteId = null;

    // Al cambiar el buscador, regresamos a la página 1
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // Abrir modal en modo "crear"
    public function create(): void
    {
        $this->resetForm();
        $this->open = true;
    }

    // Abrir modal en modo "editar"
    public function edit(int $id): void
    {
        $category = Equipment::findOrFail($id);

        $this->equipmentId    = $category->id;
        $this->name          = $category->name;
        $this->type          = $category->type;
        $this->serial_number  = $category->serial_number;

        $this->open = true;
    }

    // Cerrar modal y limpiar errores
    public function closeModal(): void
    {
        $this->open = false;
        $this->resetValidation();
    }

    public function save(): void
    {
        $validated = $this->validate();

        Equipment::updateOrCreate(['id' => $this->equipmentId], $validated,);

        // Opcional: puedes lanzar aquí un toast Livewire/Alpine
        $this->dispatch('notify', message: 'Categoría guardada correctamente.',type: 'success');

        $this->closeModal();
        $this->resetForm();
    }

    // Preparar eliminación
    public function deleteConfirmation($id): void
    {
        $this->dispatch('showConfirmationModal', userId: $id)->to(ConfirmModal::class);

    }

    #[On('deleteConfirmed')]
    public function destroy($id): void
    {
        $category = Equipment::where('id', $id)->first();
        $category->delete();
        $this->dispatch('notify', message: 'La categoria se elimino con éxito', type: 'success');
    }

    // Resetear solo campos del formulario (no buscador ni paginación)
    protected function resetForm(): void
    {
        $this->equipmentId      = null;
        $this->name             = '';
        $this->type             = '';
        $this->serial_number    = '';

    }

    public function render()
    {
        $query = Equipment::query()
            ->orderBy('name');

        if ($this->search) {
            $search = $this->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        return view('livewire.equipment-controller', [
            'categories' => $query->paginate(10),
        ]);
    }
}
