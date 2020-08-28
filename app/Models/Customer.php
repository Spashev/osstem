<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
