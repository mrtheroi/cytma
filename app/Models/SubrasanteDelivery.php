<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubrasanteDelivery extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'date',
        'delivery_note',
        'customer_id',
        'truck_id',
        'material_description',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    protected $appends = ['is_active', 'volume_m3'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function truck()
    {
        return $this->belongsTo(Trucks::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getIsActiveAttribute(): bool
    {
        return is_null($this->deleted_at);
    }

    // ✅ computed volume per trip from truck catalog
    public function getVolumeM3Attribute(): float
    {
        return (float) ($this->truck?->capacity ?? 0);
    }
}
