<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        'name', 'region_id'
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}