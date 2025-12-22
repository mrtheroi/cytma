<?php

namespace App\Livewire\Percepciones;

use App\Models\ConceptoNomina;
use App\Models\DetalleNomina;
use App\Models\PeriodoNomina;
use App\Models\RegistroNomina;
use Livewire\Component;
use Livewire\WithPagination;

class Percepciones extends Component
{
    use WithPagination;

    public $conceptos;
    public $montos = [];

    // Filtros
    public $search = '';
    public $periodoId = null;
    public $unidadNegocio = null;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        // Conceptos de tipo percepción
        $this->conceptos = ConceptoNomina::where('tipo', 'percepcion')->get();
    }

    public function guardarFila($registroId)
    {
        $registro = RegistroNomina::findOrFail($registroId);

        foreach ($this->conceptos as $con) {
            $monto = $this->montos[$registroId][$con->id] ?? 0;

            // Crear o actualizar cada detalle
            DetalleNomina::updateOrCreate(
                [
                    'registro_nomina_id' => $registroId,
                    'concepto_nomina_id' => $con->id,
                ],
                [
                    'monto' => $monto,
                ]
            );
        }

        // Recalcular el total de percepciones
        $total = DetalleNomina::where('registro_nomina_id', $registroId)
            ->whereHas('concepto', fn($q) => $q->where('tipo', 'percepcion'))
            ->sum('monto');

        $registro->update([
            'percepciones_totales' => $total,
            'neto' => $total - $registro->deducciones_totales,
        ]);
        $registro->calcularNomina();
        
        $this->dispatch(
            'notify',
            message: 'Cambios guardados correctamente',
            type: 'success'
        );
    }

    public function render()
    {
        $periodos = PeriodoNomina::orderBy('fecha_inicio', 'desc')->pluck('id');

        $regs = RegistroNomina::with([
                'empleado',
                'detalles.concepto',
                'periodoNomina',
            ])
            ->whereIn('periodo_nomina_id', $periodos)

            // 🔎 Search por empleado / unidad
            ->when($this->search, function ($q) {
                $q->whereHas('empleado', function ($e) {
                    $e->where(function ($q) {
                        $q->where('nombre', 'like', "%{$this->search}%")
                          ->orWhere('apellido_paterno', 'like', "%{$this->search}%")
                          ->orWhere('apellido_materno', 'like', "%{$this->search}%")
                          ->orWhere('unidad_negocio', 'like', "%{$this->search}%");
                    });
                });
            })

            ->orderByDesc('id')
            ->paginate(10);

        // Inicializar montos SOLO de la página actual
        foreach ($regs as $reg) {
            foreach ($this->conceptos as $con) {
                $detalle = $reg->detalles->firstWhere(
                    'concepto_nomina_id',
                    $con->id
                );

                $this->montos[$reg->id][$con->id] = $detalle?->monto ?? 0;
            }
        }

        return view('livewire.percepciones.percepciones', compact('regs'));
    }
}