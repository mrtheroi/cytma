<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\SubrasanteDelivery;
use App\Models\Trucks;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class SubrasanteDeliveryController extends Component
{
    use WithPagination;

    // Search + filters (persisted in URL)
    #[Url]
    public string $search = '';

    #[Url]
    public ?string $from = null;

    #[Url]
    public ?string $to = null;

    #[Url]
    public ?int $customer_id_filter = null;

    #[Url]
    public ?int $truck_id_filter = null;

    // Modal state
    public bool $open = false;

    public ?int $deliveryId = null;

    // Form fields (English)
    #[Rule('required|date')]
    public string $date = '';

    #[Rule('required|string|max:50')]
    public string $delivery_note = '';

    #[Rule('required|integer|exists:customers,id')]
    public ?int $customer_id = null;

    #[Rule('required|integer|exists:trucks,id')]
    public ?int $truck_id = null;

    #[Rule('required|string|max:255')]
    public string $material_description = '';

    #[Rule('nullable|string|max:1000')]
    public ?string $notes = null;

    public function mount(): void
    {
        // Default range: current month
        if (!$this->from) $this->from = now()->startOfMonth()->toDateString();
        if (!$this->to)   $this->to   = now()->endOfMonth()->toDateString();

        // Default form date
        $this->date = now()->toDateString();
    }

    // Reset pagination when filters change
    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFrom(): void { $this->resetPage(); }
    public function updatingTo(): void { $this->resetPage(); }
    public function updatingCustomerIdFilter(): void { $this->resetPage(); }
    public function updatingTruckIdFilter(): void { $this->resetPage(); }

    // Open modal (create)
    public function create(): void
    {
        $this->resetForm();
        $this->date = now()->toDateString();
        $this->open = true;
    }

    // Open modal (edit)
    public function edit(int $id): void
    {
        $row = SubrasanteDelivery::findOrFail($id);

        $this->deliveryId = $row->id;
        $this->date = $row->date->toDateString();
        $this->delivery_note = $row->delivery_note;
        $this->customer_id = $row->customer_id;
        $this->truck_id = $row->truck_id;
        $this->material_description = $row->material_description;
        $this->notes = $row->notes;

        $this->open = true;
    }

    public function closeModal(): void
    {
        $this->open = false;
        $this->resetValidation();
    }

    public function save(): void
    {
        $validated = $this->validate();

        SubrasanteDelivery::updateOrCreate(
            ['id' => $this->deliveryId],
            array_merge($validated, [
                'created_by' => auth()->id(),
            ])
        );

        $this->dispatch('notify', message: 'Record saved successfully.', type: 'success');

        $this->closeModal();
        $this->resetForm();
    }

    // Delete confirmation (same pattern as your DieselLoads)
    public function deleteConfirmation(int $id): void
    {
        $this->dispatch('showConfirmationModal', userId: $id)->to(ConfirmModal::class);
    }

    #[On('deleteConfirmed')]
    public function destroy(int $id): void
    {
        $row = SubrasanteDelivery::where('id', $id)->firstOrFail();
        $row->delete();

        $this->dispatch('notify', message: 'Record deleted successfully.', type: 'success');
    }

    protected function resetForm(): void
    {
        $this->deliveryId = null;

        $this->date = now()->toDateString();
        $this->delivery_note = '';
        $this->customer_id = null;
        $this->truck_id = null;
        $this->material_description = '';
        $this->notes = null;
    }

    /**
     * Base filtered query (detail rows)
     */
    protected function filteredQuery()
    {
        $query = SubrasanteDelivery::query()
            ->with([
                'customer:id,name',
                'truck:id,license_plate,model,type,capacity',
            ])
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc');

        // Date range
        if ($this->from) {
            $query->whereDate('date', '>=', Carbon::parse($this->from)->toDateString());
        }
        if ($this->to) {
            $query->whereDate('date', '<=', Carbon::parse($this->to)->toDateString());
        }

        // Catalog filters
        if ($this->customer_id_filter) $query->where('customer_id', $this->customer_id_filter);
        if ($this->truck_id_filter)    $query->where('truck_id', $this->truck_id_filter);

        // Search (delivery note, material, customer name, truck plate)
        if ($this->search) {
            $search = $this->search;

            $query->where(function ($q) use ($search) {
                $q->where('delivery_note', 'like', "%{$search}%")
                    ->orWhere('material_description', 'like', "%{$search}%")
                    ->orWhereHas('customer', fn ($x) => $x->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('truck', fn ($x) => $x->where('license_plate', 'like', "%{$search}%"));
            });
        }

        return $query;
    }

    public function render()
    {
        $query = $this->filteredQuery();

        // Paginated detail rows
        $deliveries = $query->paginate(10);

        // Catalogs for filters/selects
        $customers = Customer::orderBy('name')->get(['id', 'name']);
        $trucks = Trucks::orderBy('license_plate')->get(['id', 'license_plate', 'type', 'capacity']);

        /**
         * KPI totals (computed from trucks.capacity)
         * - Trips = COUNT(*) of deliveries
         * - Total m3 = SUM(trucks.capacity) for selected rows
         */
        $totalsQuery = (clone $query)->getQuery(); // base builder
        $totals = SubrasanteDelivery::query()
            ->join('trucks', 'trucks.id', '=', 'subrasante_deliveries.truck_id')
            ->when($this->from, fn ($q) => $q->whereDate('subrasante_deliveries.date', '>=', $this->from))
            ->when($this->to, fn ($q) => $q->whereDate('subrasante_deliveries.date', '<=', $this->to))
            ->when($this->customer_id_filter, fn ($q) => $q->where('subrasante_deliveries.customer_id', $this->customer_id_filter))
            ->when($this->truck_id_filter, fn ($q) => $q->where('subrasante_deliveries.truck_id', $this->truck_id_filter))
            ->when($this->search, function ($q) {
                $s = $this->search;
                $q->where(function ($qq) use ($s) {
                    $qq->where('subrasante_deliveries.delivery_note', 'like', "%{$s}%")
                        ->orWhere('subrasante_deliveries.material_description', 'like', "%{$s}%")
                        ->orWhere('trucks.license_plate', 'like', "%{$s}%");
                });
            })
            ->selectRaw('COUNT(*) as trips, COALESCE(SUM(trucks.capacity),0) as total_m3')
            ->first();

        $totalTrips = (int) ($totals->trips ?? 0);
        $totalM3 = (float) ($totals->total_m3 ?? 0);
        $avgM3PerTrip = $totalTrips > 0 ? ($totalM3 / $totalTrips) : 0;

        /**
         * Optional: Summary by truck (plates)
         * Trips = COUNT(*), Total m3 = SUM(trucks.capacity)
         */
        $summaryByTruck = SubrasanteDelivery::query()
            ->join('trucks', 'trucks.id', '=', 'subrasante_deliveries.truck_id')
            ->when($this->from, fn ($q) => $q->whereDate('subrasante_deliveries.date', '>=', $this->from))
            ->when($this->to, fn ($q) => $q->whereDate('subrasante_deliveries.date', '<=', $this->to))
            ->when($this->customer_id_filter, fn ($q) => $q->where('subrasante_deliveries.customer_id', $this->customer_id_filter))
            ->when($this->truck_id_filter, fn ($q) => $q->where('subrasante_deliveries.truck_id', $this->truck_id_filter))
            ->when($this->search, function ($q) {
                $s = $this->search;
                $q->where(function ($qq) use ($s) {
                    $qq->where('subrasante_deliveries.delivery_note', 'like', "%{$s}%")
                        ->orWhere('subrasante_deliveries.material_description', 'like', "%{$s}%")
                        ->orWhere('trucks.license_plate', 'like', "%{$s}%");
                });
            })
            ->selectRaw('subrasante_deliveries.truck_id, trucks.license_plate, trucks.type, trucks.capacity, COUNT(*) as trips, COALESCE(SUM(trucks.capacity),0) as total_m3')
            ->groupBy('subrasante_deliveries.truck_id', 'trucks.license_plate', 'trucks.type', 'trucks.capacity')
            ->orderByDesc('total_m3')
            ->get();

        return view('livewire.subrasante-delivery-controller', [
            'deliveries' => $deliveries,
            'customers' => $customers,
            'trucks' => $trucks,

            // KPI
            'totalTrips' => $totalTrips,
            'totalM3' => $totalM3,
            'avgM3PerTrip' => $avgM3PerTrip,

            // Summary
            'summaryByTruck' => $summaryByTruck,
        ]);
    }
}
