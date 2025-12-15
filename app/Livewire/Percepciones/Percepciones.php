<?php

namespace App\Livewire\Percepciones;

use App\Models\ConceptoNomina;
use App\Models\DetalleNomina;
use App\Models\Empleado;
use App\Models\PeriodoNomina;
use App\Models\RegistroNomina;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Percepciones extends Component
{
    public $registros;
    public $conceptos; // percepciones disponibles (comida, apoyo, compensación...)
    public $montos = [];

    // Filtros
    public $search = '';
    public $periodoId = null;
    public $unidadNegocio = null;

    public function mount()
    {
        // Detectar período actual
        $periodos = PeriodoNomina::orderBy('fecha_inicio', 'desc')->pluck('id');

        if (!$periodos) {
            $this->registros = collect();
            return;
        }

        // Cargar conceptos de tipo "percepcion"
        $this->conceptos = ConceptoNomina::where('tipo', 'percepcion')->get();

        // Cargar registros de nómina con detalles
        $this->registros = RegistroNomina::with(['empleado', 'detalles.concepto'])
            ->whereIn('periodo_nomina_id', $periodos)
            ->get();

        // Inicializar montos por registro y concepto
        foreach ($this->registros as $reg) {
            foreach ($this->conceptos as $con) {
                $detalle = $reg->detalles->firstWhere('concepto_nomina_id', $con->id);

                $this->montos[$reg->id][$con->id] = $detalle ? $detalle->monto : 0;
            }
        }
    }

    public function getRegistrosFiltradosProperty()
    {
        $registros = $this->registros;

        if ($this->search) {
            $search = strtolower($this->search);

            $registros = $registros->filter(function ($reg) use ($search) {
                $nombre = strtolower(
                    $reg->empleado->nombre . ' ' .
                    $reg->empleado->apellido_paterno . ' ' .
                    $reg->empleado->apellido_materno
                );

                return str_contains($nombre, $search);
            });
        }

        return $registros;
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
        LivewireAlert::title('¡Éxito!')
            ->text('Percepciones guardadas correctamente.')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function render()
    {
        return view('livewire.percepciones.percepciones', [
            'regs' => $this->registrosFiltrados,
        ]);
    }
}