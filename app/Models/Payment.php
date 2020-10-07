<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Payment extends Model
{
    use Sortable;

    protected $guarded = [];

    public $timestamps = false;

    public $sortable = ['id', 'seq', 'deadline', 'amount', 'remain'];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}