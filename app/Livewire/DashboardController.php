<?php

namespace App\Livewire;

use App\Models\DieselLoads;
use App\Models\Empleado;
use App\Models\Equipment;
use App\Models\SubrasanteDelivery;
use App\Models\Supplier;
use App\Models\Trucks;
use Carbon\Carbon;
use Livewire\Component;

class DashboardController extends Component
{
    public ?string $from = null;
    public ?string $to = null;

    public function mount(): void
    {
        // Por default: mes actual
        $this->from = now()->startOfMonth()->toDateString();
        $this->to   = now()->endOfMonth()->toDateString();
    }

    protected function applyDateRange($query, string $column = 'date')
    {
        return $query
            ->when($this->from, fn ($q) => $q->whereDate($column, '>=', Carbon::parse($this->from)->toDateString()))
            ->when($this->to, fn ($q) => $q->whereDate($column, '<=', Carbon::parse($this->to)->toDateString()));
    }

    public function render()
    {
        // Totales generales
        $employeesCount = Empleado::query()->count();
        $equipmentCount = Equipment::query()->count();
        $suppliersCount = Supplier::query()->count();
        $trucksCount    = Trucks::query()->count();

        /**
         * Diesel KPIs
         * Ajusta el nombre del campo si NO se llama "liters"
         * (por ejemplo "litros" o "amount_liters")
         */
        $dieselQuery = $this->applyDateRange(DieselLoads::query(), 'date');
        $dieselLoadsCount = (clone $dieselQuery)->count();
        $dieselTotalLiters = (float) (clone $dieselQuery)->sum('liters');

        /**
         * Subrasante KPIs
         * - Trips = COUNT(*)
         * - Total m3 = SUM(trucks.capacity)
         */
        $subrasanteQuery = $this->applyDateRange(SubrasanteDelivery::query(), 'date');

        $subrasanteTrips = (clone $subrasanteQuery)->count();

        $subrasanteTotals = SubrasanteDelivery::query()
            ->join('trucks', 'trucks.id', '=', 'subrasante_deliveries.truck_id')
            ->when($this->from, fn ($q) => $q->whereDate('subrasante_deliveries.date', '>=', $this->from))
            ->when($this->to, fn ($q) => $q->whereDate('subrasante_deliveries.date', '<=', $this->to))
            ->selectRaw('COALESCE(SUM(trucks.capacity),0) as total_m3')
            ->first();

        $subrasanteTotalM3 = (float) ($subrasanteTotals->total_m3 ?? 0);

        return view('livewire.dashboard-controller', [
            'employeesCount' => $employeesCount,
            'equipmentCount' => $equipmentCount,
            'suppliersCount' => $suppliersCount,
            'trucksCount'    => $trucksCount,

            'dieselLoadsCount'  => $dieselLoadsCount,
            'dieselTotalLiters' => $dieselTotalLiters,

            'subrasanteTrips'   => $subrasanteTrips,
            'subrasanteTotalM3' => $subrasanteTotalM3,
        ]);
    }
}
