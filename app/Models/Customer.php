<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }
}