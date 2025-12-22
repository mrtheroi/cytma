<?php

namespace App\Livewire;

use App\Models\BusinessUnit;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class BusinessUnitController extends Component
{
    use WithPagination;

    // Para buscar
    #[Url]
    public $search = '';

    // Control para el modal
    public bool $open = false;

    public ?int $unitId = null;

    #[Rule('required|string|max:150')]
    public string $name = '';

    #[Rule('nullable|string|max:255')]
    public ?string $description = '';


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
        $category = BusinessUnit::findOrFail($id);

        $this->unitId    = $category->id;
        $this->name = $category->name;
        $this->description  = $category->description;

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

        BusinessUnit::updateOrCreate(['id' => $this->unitId], $validated,);

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
        $category = BusinessUnit::where('id', $id)->first();
        $category->delete();
        $this->dispatch('notify', message: 'La categoria se elimino con éxito', type: 'success');
    }

    // Resetear solo campos del formulario (no buscador ni paginación)
    protected function resetForm(): void
    {
        $this->categoryId    = null;
        $this->name          = '';
        $this->description   = '';
    }

    public function render()
    {
        $query = BusinessUnit::query()
            ->orderBy('name');

        if ($this->search) {
            $search = $this->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        return view('livewire.unidad-negocio', [
            'categories' => $query->paginate(10),
        ]);
    }
}
