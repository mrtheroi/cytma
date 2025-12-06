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
}
