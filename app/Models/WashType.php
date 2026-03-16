<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WashType extends Model
{
    protected $fillable = ['name', 'price', 'vehicle_type'];

    public function washes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Wash::class);
    }
}
