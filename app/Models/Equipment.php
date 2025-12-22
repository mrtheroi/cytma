<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'description',
    ];

    protected $appends = ['is_active'];

    public function getIsActiveAttribute(): bool
    {
        return is_null($this->deleted_at);
    }
}
