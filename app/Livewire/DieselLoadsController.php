<?php

namespace App\Livewire;

use App\Models\BusinessUnit;
use App\Models\DieselLoads;
use App\Models\Empleado;
use App\Models\Equipment;
use App\Models\Supplier;
use Carbon\Carbon;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\DieselLogsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DieselLoadsController extends Component
{

    use WithPagination;

    // Buscar (URL)
    #[Url]
    public string $search = '';

    // Filtros (URL opcional si quieres que se persistan)
    #[Url]
    public ?string $from = null;

    #[Url]
    public ?string $to = null;

    #[Url]
    public ?int $business_unit_id_filter = null;

    #[Url]
    public ?int $supplier_id_filter = null;

    #[Url]
    public ?int $equipment_id_filter = null;

    #[Url]
    public ?int $empleado_id_filter = null;

    // Modal
    public bool $open = false;

    public ?int $dieselLoadsId = null;

    // Campos del formulario
    #[Rule('required|date')]
    public string $date = '';

    #[Rule('required|integer|exists:business_units,id')]
    public ?int $business_unit_id = null;

    #[Rule('required|integer|exists:suppliers,id')]
    public ?int $supplier_id = null;

    #[Rule('required|integer|exists:equipment,id')]
    public ?int $equipment_id = null;

    #[Rule('required|integer|exists:empleados,id')]
    public ?int $empleado_id = null;

    #[Rule('required|numeric|min:0|max:99999999.99')]
    public ?string $hour_meter = null;

    #[Rule('required|numeric|min:0.01|max:99999999.99')]
    public ?string $liters = null;

    #[Rule('nullable|string|max:500')]
    public ?string $notes = null;

    // Al cambiar el buscador o filtros, regresamos a la página 1
    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFrom(): void { $this->resetPage(); }
    public function updatingTo(): void { $this->resetPage(); }
    public function updatingBusinessUnitIdFilter(): void { $this->resetPage(); }
    public function updatingSupplierIdFilter(): void { $this->resetPage(); }
    public function updatingEquipmentIdFilter(): void { $this->resetPage(); }
    public function updatingEmpleadoIdFilter(): void { $this->resetPage(); }

    public function mount(): void
    {
        // Defaults de fechas (mes actual) si no vienen en URL
        if (!$this->from) $this->from = now()->startOfMonth()->toDateString();
        if (!$this->to)   $this->to   = now()->endOfMonth()->toDateString();

        // Default fecha de captura
        $this->date = now()->toDateString();
    }

    // Abrir modal en modo "crear"
    public function create(): void
    {
        $this->resetForm();
        $this->date = now()->toDateString();
        $this->open = true;
    }

    // Abrir modal en modo "editar"
    public function edit(int $id): void
    {
        $log = DieselLoads::findOrFail($id);

        $this->dieselLoadsId = $log->id;
        $this->date = $log->date->toDateString();

        $this->business_unit_id = $log->business_unit_id;
        $this->supplier_id      = $log->supplier_id;
        $this->equipment_id     = $log->equipment_id;
        $this->empleado_id      = $log->empleado_id;

        $this->hour_meter = $log->hour_meter !== null ? (string) $log->hour_meter : null;
        $this->liters     = (string) $log->liters;
        $this->notes      = $log->notes;

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

        // Casts/control extra (por si llegan strings)
        $validated['hour_meter'] = $validated['hour_meter'] !== null ? (float)$validated['hour_meter'] : null;
        $validated['liters'] = (float)$validated['liters'];

        DieselLoads::updateOrCreate(
            ['id' => $this->dieselLoadsId],
            array_merge($validated, [
                'created_by' => auth()->id(),
            ])
        );

        $this->dispatch('notify', message: 'Carga de diésel guardada correctamente.', type: 'success');

        $this->closeModal();
        $this->resetForm();
    }

    // Confirmar eliminación (igual que tu patrón)
    public function deleteConfirmation($id): void
    {
        $this->dispatch('showConfirmationModal', userId: $id)->to(ConfirmModal::class);
    }

    #[On('deleteConfirmed')]
    public function destroy($id): void
    {
        $log = DieselLoads::where('id', $id)->firstOrFail();
        $log->delete();

        $this->dispatch('notify', message: 'Registro eliminado con éxito.', type: 'success');
    }

    protected function resetForm(): void
    {
        $this->dieselLoadsId = null;

        $this->date = now()->toDateString();

        $this->business_unit_id = null;
        $this->supplier_id = null;
        $this->equipment_id = null;
        $this->empleado_id = null;

        $this->hour_meter = null;
        $this->liters = null;
        $this->notes = null;
    }

    public function resetFilters(): void
    {
        $this->reset([
            'search',
            'business_unit_id_filter',
            'supplier_id_filter',
            'equipment_id_filter',
            'empleado_id_filter',
        ]);

        $this->from = now()->startOfMonth()->toDateString();
        $this->to   = now()->endOfMonth()->toDateString();

        $this->resetPage();
    }

    public function render()
    {
        $query = DieselLoads::query()
            ->with(['businessUnit', 'supplier', 'equipment', 'empleado'])
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc');

        // filtros... (los mismos que ya tienes)
        if ($this->from) $query->whereDate('date', '>=', $this->from);
        if ($this->to)   $query->whereDate('date', '<=', $this->to);

        if ($this->business_unit_id_filter) $query->where('business_unit_id', $this->business_unit_id_filter);
        if ($this->supplier_id_filter)      $query->where('supplier_id', $this->supplier_id_filter);
        if ($this->equipment_id_filter)     $query->where('equipment_id', $this->equipment_id_filter);
        if ($this->empleado_id_filter)      $query->where('empleado_id', $this->empleado_id_filter);

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('notes', 'like', "%{$search}%")
                    ->orWhereHas('businessUnit', fn($x) => $x->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('supplier', fn($x) => $x->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('equipment', fn($x) => $x->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('empleado', fn($x) => $x->where('nombre', 'like', "%{$search}%"));
            });
        }

        // ✅ Totales que reaccionan a filtros (no afectan paginación)
        $totalsQuery = (clone $query);
        $totalLiters = (float) $totalsQuery->sum('liters');
        $totalRows   = (clone $query)->count();
        $avgLiters   = $totalRows > 0 ? ($totalLiters / $totalRows) : 0;

        // catálogos
        $businessUnits = BusinessUnit::orderBy('name')->get(['id','name']);
        $suppliers     = Supplier::orderBy('name')->get(['id','name']);
        $equipment     = Equipment::orderBy('name')->get(['id','name']);
        $empleados     = Empleado::orderBy('nombre')->get(['id','nombre','apellido_paterno']);

        return view('livewire.diesel-log-controller', [
            'logs'          => $query->paginate(10),
            'businessUnits' => $businessUnits,
            'suppliers'     => $suppliers,
            'equipment'     => $equipment,
            'empleados'     => $empleados,

            // ✅ KPI Cards
            'totalLiters' => $totalLiters,
            'totalRows'   => $totalRows,
            'avgLiters'   => $avgLiters,
        ]);
    }

    protected function filteredQuery()
    {
        $query = DieselLoads::query()
            ->with(['businessUnit', 'supplier', 'equipment', 'empleado'])
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc');

        if ($this->from) $query->whereDate('date', '>=', $this->from);
        if ($this->to)   $query->whereDate('date', '<=', $this->to);

        if ($this->business_unit_id_filter) $query->where('business_unit_id', $this->business_unit_id_filter);
        if ($this->supplier_id_filter)      $query->where('supplier_id', $this->supplier_id_filter);
        if ($this->equipment_id_filter)     $query->where('equipment_id', $this->equipment_id_filter);
        if ($this->empleado_id_filter)      $query->where('empleado_id', $this->empleado_id_filter);

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('notes', 'like', "%{$search}%")
                    ->orWhereHas('businessUnit', fn($x) => $x->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('supplier', fn($x) => $x->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('equipment', fn($x) => $x->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('empleado', fn($x) => $x->where('nombre', 'like', "%{$search}%"));
            });
        }

        return $query;
    }

    public function exportExcel()
    {
        $query = $this->filteredQuery();

        $company = 'CONCRETOS Y TRITURADOS MONTES AZULES SA DE CV';
        $from = $this->from ?? '';
        $to = $this->to ?? '';


        $filename = 'bitacora_diesel_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new DieselLogsExport($query, $company, $from, $to),
            $filename
        );
    }

    public function exportPdf()
    {
        $query = $this->filteredQuery();

        // OJO: para PDF conviene limitar o paginar si hay demasiados
        $rows = $query->get();

        $totalLiters = (float) (clone $query)->sum('liters');
        $totalRows   = $rows->count();

        $pdf = Pdf::loadView('reportes.diesel-logs', [
            'rows' => $rows,
            'from' => $this->from,
            'to' => $this->to,
            'totalLiters' => $totalLiters,
            'totalRows' => $totalRows,
        ])->setPaper('a4', 'landscape');

        $filename = 'bitacora_diesel_' . now()->format('Ymd_His') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }



}
