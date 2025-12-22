<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'contact_email',
        'phone_number',
    ];

    protected $appends = ['is_active'];

    public function getIsActiveAttribute(): bool
    {
        return is_null($this->deleted_at);
    }
}
