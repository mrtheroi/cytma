<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeriodoNomina extends Model
{
    // use SoftDeletes;

    protected $table = 'periodos_nomina';

    protected $fillable = [
        'fecha_inicio',
        'fecha_fin',
        'unidad_negocio',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    //
    public function registros()
    {
        return $this->hasMany(RegistroNomina::class);
    }
}
