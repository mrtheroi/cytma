<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DieselLoads extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'date',
        'business_unit_id',
        'supplier_id',
        'equipment_id',
        'empleado_id',
        'hour_meter',
        'liters',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'hour_meter' => 'decimal:2',
        'liters' => 'decimal:2',
    ];

    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Si quieres un "is_active" basado en SoftDeletes
    protected $appends = ['is_active'];

    public function getIsActiveAttribute(): bool
    {
        return is_null($this->deleted_at);
    }
}
