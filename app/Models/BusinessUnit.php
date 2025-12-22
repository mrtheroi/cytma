<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessUnit extends Model
{
    use SoftDeletes;
    protected $table = 'business_units';
    protected $fillable = [
        'name',
        'description',
    ];

    protected $appends = ['is_active'];

    public function getIsActiveAttribute(): bool
    {
        return is_null($this->deleted_at);
    }
}
