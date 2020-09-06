<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}