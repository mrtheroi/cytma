<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'asistencias';

    protected $fillable = [
        'empleado_id',
        'fecha',
        'entrada',
        'salida',
        'entrada_extra',
        'salida_extra',
        'horas_extra',
    ];

    protected $casts = [
        'entrada'        => 'datetime',
        'salida'         => 'datetime',
        'entrada_extra'  => 'datetime',
        'salida_extra'   => 'datetime',
        'fecha'          => 'date:Y-m-d',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    //
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
