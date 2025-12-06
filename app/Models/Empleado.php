<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empleado extends Model
{
    // use SoftDeletes;

    protected $table = 'empleados';

    protected $fillable = [
        'user_id',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'turno',
        'categoria',
        'unidad_negocio',
        'adscrito',
        'sueldo_pactado',
        'costo_dia',
        'costo_hora',
        'costo_hora_extra',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registros()
    {
        return $this->hasMany(RegistroNomina::class);
    }
}
