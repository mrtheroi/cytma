<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleNomina extends Model
{
    // use SoftDeletes;

    protected $table = 'detalles_nomina';

    protected $fillable = [
        'registro_nomina_id',
        'concepto_nomina_id',
        'monto',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relaciones
    // public function registroNomina()
    // {
    //     return $this->belongsTo(RegistroNomina::class);
    // }

    // public function conceptoNomina()
    // {
    //     return $this->belongsTo(ConceptoNomina::class);
    // }

    //
    public function concepto()
    {
        return $this->belongsTo(ConceptoNomina::class, 'concepto_nomina_id');
    }
}
