<?php

namespace App\Livewire\Periodos;

use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Models\PeriodoNomina;
use Carbon\Carbon;
use Livewire\Component;
use Flux\Flux;
use Barryvdh\DomPDF\Facade\Pdf;

class Periodos extends Component
{
    public $search;
    public $unidad_negocio;
    public $editingId;

    public $fecha_inicio_crear;
    public $fecha_fin_crear;

    public $periodo_detalle;

    public function mount()
    {
        $this->fecha_inicio_crear = now()->startOfWeek(Carbon::MONDAY);
        $this->fecha_fin_crear = now()->endOfWeek(Carbon::SUNDAY);
    }

    public function verDetalles($id)
    {
        $this->periodo_detalle = PeriodoNomina::find($id);
        Flux::modal('detalle-periodo')->show();
    }

    public function modalCrearPeriodo()
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->resetValidation();

        // Calcular el siguiente periodo automáticamente
        $ultimo = PeriodoNomina::orderBy('fecha_fin', 'desc')->first();

        if (!$ultimo) {
            // Primer periodo automático
            $this->fecha_inicio_crear = now()->startOfWeek(Carbon::MONDAY);
            $this->fecha_fin_crear = now()->endOfWeek(Carbon::SUNDAY);
        } else {
            $this->fecha_inicio_crear = $ultimo->fecha_fin->copy()->addDay();
            $this->fecha_fin_crear = $this->fecha_inicio_crear->copy()->addDays(6);
        }

        Flux::modal('nuevo-periodo')->show();
    }

    public function resetForm()
    {
        //
    }

    public function generarReporte($periodoId)
    {
        $periodo = PeriodoNomina::with([
            'registros.empleado',
            'registros.asistencias',
            'registros.detalles.concepto'
        ])->findOrFail($periodoId);

        $datosReporte = $periodo->registros->map(function($registro) {
            $asistencia = $registro->asistenciaNomina ?? null;

            $diasTrabajados = collect([
                $asistencia?->lunes,
                $asistencia?->martes,
                $asistencia?->miercoles,
                $asistencia?->jueves,
                $asistencia?->viernes,
                $asistencia?->sabado,
                $asistencia?->domingo,
            ])->filter()->count();

            $horasExtras = collect([
                $asistencia?->horas_extra_lunes,
                $asistencia?->horas_extra_martes,
                $asistencia?->horas_extra_miercoles,
                $asistencia?->horas_extra_jueves,
                $asistencia?->horas_extra_viernes,
                $asistencia?->horas_extra_sabado,
                $asistencia?->horas_extra_domingo,
            ])->sum();

            $bonos = $registro->detalles
                ->filter(fn($d) => $d->concepto->tipo === 'percepcion')
                ->sum('monto');

            $descuentos = $registro->detalles
                ->filter(fn($d) => $d->concepto->tipo === 'deduccion')
                ->sum('monto');

            return [
                'empleado' => $registro->empleado->nombre . ' ' . $registro->empleado->apellido_paterno,
                'pactado' => $registro->sueldo_pactado,
                'turno' => $registro->empleado->turno,
                'categoria' => $registro->empleado->categoria,
                'dias_trabajados' => $diasTrabajados,
                'horas_extras' => $horasExtras,
                'bonos' => $bonos,
                'descuentos' => $descuentos,
                'neto' => $registro->neto,
            ];
        });

        $pdf = Pdf::loadView('reportes.reporte_nomina', [
            'periodo' => $periodo,
            'datosReporte' => $datosReporte,
        ]);

        return response()->streamDownload(fn() => print($pdf->output()), "Nomina_{$periodo->fecha_inicio->format('Ymd')}_{$periodo->fecha_fin->format('Ymd')}.pdf");
    }

    public function crearPeriodo()
    {
        // Validar si ya existe
        $existe = PeriodoNomina::where('fecha_inicio', $this->fecha_inicio_crear)
            ->where('fecha_fin', $this->fecha_fin_crear)
            ->exists();

        if ($existe) {
            $this->dispatch('notify', [
                'title' => 'Periodo ya existe',
                'message' => "El periodo {$this->fecha_inicio_crear->format('d/m/y')} al {$this->fecha_fin_crear->format('d/m/y')} ya se encuentra registrado.",
                'type' => 'warning'
            ]);
            return;
        }

        PeriodoNomina::create([
            'fecha_inicio' => $this->fecha_inicio_crear,
            'fecha_fin' => $this->fecha_fin_crear,
            'unidad_negocio' => $this->unidad_negocio, // si quieres asignar unidad
        ]);

        // $this->dispatch('notify', [
        //     'title' => 'Periodo creado',
        //     'message' => "Se creó el periodo del {$this->fecha_inicio_crear->format('d/m/y')} al {$this->fecha_fin_crear->format('d/m/y')}",
        //     'type' => 'success'
        // ]);

        LivewireAlert::title('¡Éxito!')
            ->text('Periodo creado correctamente')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();

        Flux::modal('nuevo-periodo')->close();
    }

    public function render()
    {
        $query = PeriodoNomina::query();
        //$query = $this->applySearch($query);

        $periodos = $query->paginate(10);

        return view('livewire.periodos.periodos', compact('periodos'));
    }
}
