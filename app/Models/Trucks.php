<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trucks extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'license_plate',
        'model',
        'type',
        'capacity',
        'description',
    ];

    protected $appends = ['is_active'];

    public function getIsActiveAttribute(): bool
    {
        return is_null($this->deleted_at);
    }
}
