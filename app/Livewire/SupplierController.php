<?php

namespace App\Livewire;

use App\Models\Supplier;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class SupplierController extends Component
{
    use WithPagination;

    // Para buscar
    #[Url]
    public $search = '';

    // Control para el modal
    public bool $open = false;

    public ?int $supplierId = null;

    #[Rule('required|string|max:150')]
    public string $name = '';

    #[Rule('email|max:150')]
    public ?string $contact_email = '';

    #[Rule('string|max:10')]
    public ?string $phone_number = '';



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

        $this->supplierId    = $category->id;
        $this->name          = $category->name;
        $this->contact_email = $category->contact_email;
        $this->phone_number  = $category->phone_number;

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

        Supplier::updateOrCreate(['id' => $this->supplierId], $validated,);

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
        $category = Supplier::where('id', $id)->first();
        $category->delete();
        $this->dispatch('notify', message: 'La categoria se elimino con éxito', type: 'success');
    }

    // Resetear solo campos del formulario (no buscador ni paginación)
    protected function resetForm(): void
    {
        $this->supplierId       = null;
        $this->name             = '';
        $this->contact_email    = '';
        $this->phone_number     = '';

    }

    public function render()
    {
        $query = Supplier::query()
            ->orderBy('name');

        if ($this->search) {
            $search = $this->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        return view('livewire.supplier-controller', [
            'categories' => $query->paginate(10),
        ]);
    }
}
