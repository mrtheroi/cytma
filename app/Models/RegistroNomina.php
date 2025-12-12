<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistroNomina extends Model
{
    // use SoftDeletes;

    protected $table = 'registros_nomina';

    protected $fillable = [
        'periodo_nomina_id',
        'empleado_id',
        'sueldo_pactado',
        'percepciones_totales',
        'deducciones_totales',
        'total_horas_extras',
        'neto',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relaciones sugeridas
    public function periodoNomina()
    {
        return $this->belongsTo(PeriodoNomina::class);
    }

    // public function empleado()
    // {
    //     return $this->belongsTo(Empleado::class);
    // }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function asistencias()
    {
        return $this->hasOne(AsistenciaNomina::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleNomina::class);
    }

    public function calcularNomina()
    {
        $empleado = $this->empleado;
        $asistencia = $this->asistencias; // relación 1:1

        if (!$empleado || !$asistencia) {
            return; // No se puede calcular sin estos datos
        }

        /*
        |--------------------------------------------------------------------------
        | 1) CALCULAR DÍAS TRABAJADOS SEGÚN asistencias_nomina
        |--------------------------------------------------------------------------
        */
        $dias = [
            'lunes'     => $asistencia->lunes ? 1 : 0,
            'martes'    => $asistencia->martes ? 1 : 0,
            'miercoles' => $asistencia->miercoles ? 1 : 0,
            'jueves'    => $asistencia->jueves ? 1 : 0,
            'viernes'   => $asistencia->viernes ? 1 : 0,
            'sabado'    => $asistencia->sabado ? 1 : 0,
            'domingo'   => $asistencia->domingo ? 1 : 0,
        ];

        $totalDias = array_sum($dias);


        /*
        |--------------------------------------------------------------------------
        | 2) CALCULAR HORAS EXTRA DEL PERIODO
        |--------------------------------------------------------------------------
        */
        $horas = [
            $asistencia->horas_extra_lunes,
            $asistencia->horas_extra_martes,
            $asistencia->horas_extra_miercoles,
            $asistencia->horas_extra_jueves,
            $asistencia->horas_extra_viernes,
            $asistencia->horas_extra_sabado,
            $asistencia->horas_extra_domingo,
        ];

        $totalHorasExtra = array_sum($horas);


        /*
        |--------------------------------------------------------------------------
        | 3) COSTOS BASE DEL EMPLEADO
        |--------------------------------------------------------------------------
        */
        $pagoDias      = $totalDias * $empleado->costo_dia;
        $pagoHorasEx   = $totalHorasExtra * $empleado->costo_hora_extra;


        /*
        |--------------------------------------------------------------------------
        | 4) PERCEPCIONES ADICIONALES
        |    (comidas, apoyo, compensación, etc.)
        |--------------------------------------------------------------------------
        */
        $totalPercepcionesExtra = $this->detalles()
            ->whereHas('concepto', fn($q) => $q->where('tipo', 'percepcion'))
            ->sum('monto');


        /*
        |--------------------------------------------------------------------------
        | 5) DEDUCCIONES (anticipos, préstamos, etc.)
        |--------------------------------------------------------------------------
        */
        $totalDeducciones = $this->detalles()
            ->whereHas('concepto', fn($q) => $q->where('tipo', 'deduccion'))
            ->sum('monto');


        /*
        |--------------------------------------------------------------------------
        | 6) TOTALIZAR NÓMINA
        |--------------------------------------------------------------------------
        */
        $totalPercepciones = $pagoDias + $pagoHorasEx + $totalPercepcionesExtra;

        $neto = $totalPercepciones - $totalDeducciones;


        /*
        |--------------------------------------------------------------------------
        | 7) GUARDAR RESULTADOS EN registros_nomina
        |--------------------------------------------------------------------------
        */
        $this->update([
            'percepciones_totales' => $totalPercepciones,
            'deducciones_totales'  => $totalDeducciones,
            'total_horas_extras'   => $totalHorasExtra,
            'neto'                 => $neto,
        ]);
    }
}
