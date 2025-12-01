<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsistenciaNomina extends Model
{
    // use SoftDeletes;

    protected $table = 'asistencias_nomina';

    protected $fillable = [
        'registro_nomina_id',
        'lunes',
        'horas_extra_lunes',
        'martes',
        'horas_extra_martes',
        'miercoles',
        'horas_extra_miercoles',
        'jueves',
        'horas_extra_jueves',
        'viernes',
        'horas_extra_viernes',
        'sabado',
        'horas_extra_sabado',
        'domingo',
        'horas_extra_domingo',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relación con RegistroNomina
    public function registroNomina()
    {
        return $this->belongsTo(RegistroNomina::class);
    }
}
