<?php

namespace App\Livewire;

use App\Models\DieselLoads;
use App\Models\Empleado;
use App\Models\Equipment;
use App\Models\SubrasanteDelivery;
use App\Models\Supplier;
use App\Models\Trucks;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;

class DashboardController extends Component
{
    public ?string $from = null;
    public ?string $to = null;

    // Chart payload (initial render)
    public array $chartLabels = [];
    public array $chartDiesel = [];
    public array $chartSubrasante = [];

    public function mount(): void
    {
        // Por default: mes actual
        $this->from = now()->startOfMonth()->toDateString();
        $this->to   = now()->endOfMonth()->toDateString();

        // Inicializa chart para primer render
        $this->syncChartState();
    }

    public function updatedFrom(): void
    {
        $this->syncChartState(dispatchEvent: true);
    }

    public function updatedTo(): void
    {
        $this->syncChartState(dispatchEvent: true);
    }

    public function resetFilters(): void
    {
        $this->from = now()->startOfMonth()->toDateString();
        $this->to   = now()->endOfMonth()->toDateString();

        $this->syncChartState(dispatchEvent: true);
    }

    protected function applyDateRange($query, string $column = 'date')
    {
        return $query
            ->when($this->from, fn ($q) => $q->whereDate($column, '>=', Carbon::parse($this->from)->toDateString()))
            ->when($this->to, fn ($q) => $q->whereDate($column, '<=', Carbon::parse($this->to)->toDateString()));
    }

    /**
     * Build chart series using the SAME from/to as the dashboard.
     */
    private function buildChartPayload(): array
    {
        $from = Carbon::parse($this->from)->startOfDay();
        $to   = Carbon::parse($this->to)->endOfDay();

        // Labels per day (no missing days)
        $period = CarbonPeriod::create($from->toDateString(), $to->toDateString());
        $labels = [];
        foreach ($period as $d) $labels[] = $d->format('Y-m-d');

        // Diesel: SUM(liters) per day (adjust if column name differs)
        $dieselRows = DieselLoads::query()
            ->whereBetween('date', [$from, $to])
            ->selectRaw('DATE(date) as day, COALESCE(SUM(liters),0) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $dieselSeries = array_map(fn ($day) => (float)($dieselRows[$day] ?? 0), $labels);

        // Subrasante: SUM(trucks.capacity) per day
        $subRows = SubrasanteDelivery::query()
            ->join('trucks', 'trucks.id', '=', 'subrasante_deliveries.truck_id')
            ->whereBetween('subrasante_deliveries.date', [$from, $to])
            ->selectRaw('DATE(subrasante_deliveries.date) as day, COALESCE(SUM(trucks.capacity),0) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $subSeries = array_map(fn ($day) => (float)($subRows[$day] ?? 0), $labels);

        return [
            'labels' => $labels,
            'diesel' => $dieselSeries,
            'subrasante' => $subSeries,
        ];
    }

    /**
     * Sync server state for initial render + optionally dispatch for Chart.js updates.
     */
    private function syncChartState(bool $dispatchEvent = false): void
    {
        $payload = $this->buildChartPayload();

        $this->chartLabels = $payload['labels'];
        $this->chartDiesel = $payload['diesel'];
        $this->chartSubrasante = $payload['subrasante'];

        if ($dispatchEvent) {
            $this->dispatch('charts-updated', ...$payload);
        }
    }

    public function render()
    {
        // Totales generales
        $employeesCount = Empleado::query()->count();
        $equipmentCount = Equipment::query()->count();
        $suppliersCount = Supplier::query()->count();
        $trucksCount    = Trucks::query()->count();

        // Diesel KPIs
        $dieselQuery = $this->applyDateRange(DieselLoads::query(), 'date');
        $dieselLoadsCount = (clone $dieselQuery)->count();
        $dieselTotalLiters = (float) (clone $dieselQuery)->sum('liters');

        // Subrasante KPIs
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

            // chart initial payload for the blade
            'chartLabels' => $this->chartLabels,
            'chartDiesel' => $this->chartDiesel,
            'chartSubrasante' => $this->chartSubrasante,
        ]);
    }
}
