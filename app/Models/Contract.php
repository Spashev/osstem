<?php

namespace App\Models;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }
}