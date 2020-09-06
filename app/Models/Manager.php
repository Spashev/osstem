<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}