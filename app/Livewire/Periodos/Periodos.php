<?php

namespace App\Livewire\Periodos;

use App\Models\AsistenciaNomina;
use App\Models\DetalleNomina;
use App\Models\Empleado;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Models\PeriodoNomina;
use App\Models\RegistroNomina;
use Carbon\Carbon;
use Livewire\Component;
use Flux\Flux;
use Barryvdh\DomPDF\Facade\Pdf;

class Periodos extends Component
{
    public $search;
    public $unidad_negocio = '';
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
            'registros',
            'registros.empleado',
            'registros.asistencias',
            'registros.detalles.concepto'
        ])->findOrFail($periodoId);

        $datosReporte = $periodo->registros->map(function($registro) {

            $a = $registro->asistencias;

            // Días trabajados
            $dias = [
                'lunes'     => $a?->lunes ? 1 : 0,
                'martes'    => $a?->martes ? 1 : 0,
                'miercoles' => $a?->miercoles ? 1 : 0,
                'jueves'    => $a?->jueves ? 1 : 0,
                'viernes'   => $a?->viernes ? 1 : 0,
                'sabado'    => $a?->sabado ? 1 : 0,
                'domingo'   => $a?->domingo ? 1 : 0,
            ];

            // Horas extras
            $horas = [
                'lunes'     => $a?->horas_extra_lunes ?? 0,
                'martes'    => $a?->horas_extra_martes ?? 0,
                'miercoles' => $a?->horas_extra_miercoles ?? 0,
                'jueves'    => $a?->horas_extra_jueves ?? 0,
                'viernes'   => $a?->horas_extra_viernes ?? 0,
                'sabado'    => $a?->horas_extra_sabado ?? 0,
                'domingo'   => $a?->horas_extra_domingo ?? 0,
            ];

            // Totales
            $totalDias = array_sum($dias);
            $totalHoras = array_sum($horas);

            // Costos
            $costoDia = $registro->empleado->costo_dia;
            $costoHora = $registro->empleado->costo_hora;

            $horasExtraPagar = $totalHoras * $costoHora;
            $totalDiasPagar = $totalDias * $costoDia;

            // Conceptos
            // $bonos = $registro->detalles
            //     ->filter(fn($d) => $d->concepto->tipo === 'percepcion')
            //     ->sum('monto');

            // $descuentos = $registro->detalles
            //     ->filter(fn($d) => $d->concepto->tipo === 'deduccion')
            //     ->sum('monto');

            $comidas = $registro->detalles
                ->filter(fn($d) => strtolower($d->concepto->nombre) === 'comidas')
                ->sum('monto');

            $compensacion = $registro->detalles
                ->filter(fn($d) => strtolower($d->concepto->nombre) === 'compensacion')
                ->sum('monto');

            $apoyo = $registro->detalles
                ->filter(fn($d) => strtolower($d->concepto->nombre) === 'apoyo_pasajes_y_estimulos')
                ->sum('monto');

            $anticipo = $registro->detalles
                ->filter(fn($d) => strtolower($d->concepto->nombre) === 'anticipo')
                ->sum('monto');
            // $porPagar = $registro->detalles
            //     ->filter(fn($d) => strtolower($d->concepto->nombre) === 'por_pagar')
            //     ->sum('monto');

            // ---- CALCULOS IMPORTANTES ----

            // Percepción Total igual a su reporte original
            $percepcionTotal =
                ($totalDias * $costoDia) +
                $horasExtraPagar +
                //$bonos +
                $comidas +
                $compensacion +
                $apoyo;

            // Deducciones Totales
            //$deduccionesTotal = $descuentos + $anticipo + $porPagar;
            $deduccionesTotal = $anticipo;

            // Neto / Saldo Final
            $neto = $percepcionTotal - $deduccionesTotal;

            return [
                'empleado'            => $registro->empleado->nombre.' '.$registro->empleado->apellido_paterno,
                'pactado'             => $registro->sueldo_pactado,
                'turno'               => $registro->empleado->turno,
                'categoria'           => $registro->empleado->categoria,

                'dias'                => $dias,
                'horas'               => $horas,

                'total_dias'          => $totalDias,
                'total_horas'         => $totalHoras,

                'costo_dia'           => $costoDia,
                'costo_hora'          => $costoHora,
                'costo_hora_extra'    => $registro->empleado->costo_hora_extra,

                'horas_extra_pagar'   => $horasExtraPagar,

                'dia_hr_extra'        => $totalDiasPagar + $horasExtraPagar,

                'comidas'             => $comidas,
                'compensacion'        => $compensacion,
                'apoyo'               => $apoyo,

                'anticipo'            => $anticipo,
                'por_pagar'           => $percepcionTotal,

                // 'bonos'               => $bonos,
                // 'descuentos'          => $descuentos,

                'percepcion_total'    => $totalDiasPagar + $compensacion + $horasExtraPagar,
                'deducciones'         => $deduccionesTotal,
                'neto'                => $neto,
            ];
        });

        $pdf = Pdf::loadView('reportes.reporte_nomina', [
            'periodo' => $periodo,
            'datosReporte' => $datosReporte,
        ])->setPaper('tabloid', 'landscape');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            "Nomina_{$periodo->fecha_inicio->format('Ymd')}_{$periodo->fecha_fin->format('Ymd')}.pdf"
        );
    }

    public function crearPeriodo()
    {
        // Validar si ya existe
        $existe = PeriodoNomina::where('fecha_inicio', $this->fecha_inicio_crear)
            ->where('fecha_fin', $this->fecha_fin_crear)
            ->where('unidad_negocio', $this->unidad_negocio)
            ->exists();

        if ($existe) {
            $this->dispatch('notify', [
                'title' => 'Periodo ya existe',
                'message' => "El periodo {$this->fecha_inicio_crear->format('d/m/y')} al {$this->fecha_fin_crear->format('d/m/y')} ya se encuentra registrado.",
                'type' => 'warning'
            ]);
            return;
        }

        $periodoCreado = PeriodoNomina::create([
            'fecha_inicio' => $this->fecha_inicio_crear,
            'fecha_fin' => $this->fecha_fin_crear,
            'unidad_negocio' => $this->unidad_negocio, // si quieres asignar unidad
        ]);

        $empleados = Empleado::where('unidad_negocio', $this->unidad_negocio)->get();

        if($empleados->isNotEmpty()){
            foreach ($empleados as $empleado) {
                // Crear registro de nómina para cada empleado
                $registroNomina = RegistroNomina::create([
                    'periodo_nomina_id' => $periodoCreado->id,
                    'empleado_id' => $empleado->id,
                    'sueldo_pactado' => $empleado->sueldo_pactado,
                ]);

                DetalleNomina::create([
                    'registro_nomina_id' => $registroNomina->id,
                    'concepto_nomina_id' => 4, // asignamos el concepto de "anticipo" por defecto pero en 0 ya que esto se puede modificar desde usuarios
                    'monto' => 0,
                ]);

                AsistenciaNomina::create([
                    'registro_nomina_id' => $registroNomina->id,
                ]);
            }
        }

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
