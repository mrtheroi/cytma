<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConceptoNomina extends Model
{
    // use SoftDeletes;

    protected $table = 'conceptos_nomina';

    protected $fillable = [
        'tipo',
        'nombre',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
