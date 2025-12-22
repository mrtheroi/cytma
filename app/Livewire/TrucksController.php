<?php

namespace App\Livewire;

use App\Models\Supplier;
use App\Models\Trucks;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TrucksController extends Component
{
    use WithPagination;

    // Para buscar
    #[Url]
    public $search = '';

    // Control para el modal
    public bool $open = false;

    public ?int $truckId = null;

    #[Rule('required|string|max:50')]
    public string $license_plate = '';

    #[Rule('required|string|max:50')]
    public string $model = '';

    #[Rule('required|string|max:50')]
    public string $type = '';

    #[Rule('required|string|max:50')]
    public string $capacity = '';

    #[Rule('required|string|max:100')]
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
        $category = Supplier::findOrFail($id);

        $this->supplierId       = $category->id;
        $this->license_plate    = $category->license_plate;
        $this->model            = $category->model;
        $this->type             = $category->type;
        $this->description      = $category->description;

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

        Trucks::updateOrCreate(['id' => $this->truckId], $validated,);

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
        $category = Trucks::where('id', $id)->first();
        $category->delete();
        $this->dispatch('notify', message: 'La categoria se elimino con éxito', type: 'success');
    }

    // Resetear solo campos del formulario (no buscador ni paginación)
    protected function resetForm(): void
    {
        $this->truckId              = null;
        $this->license_plate        = '';
        $this->model                = '';
        $this->type                 = '';
        $this->description          = '';

    }

    public function render()
    {
        $query = Trucks::query()
            ->orderBy('license_plate');

        if ($this->search) {
            $search = $this->search;

            $query->where(function ($q) use ($search) {
                $q->where('license_plate', 'like', "%{$search}%");
            });
        }

        return view('livewire.trucks-controller', [
            'categories' => $query->paginate(10),
        ]);
    }
}
