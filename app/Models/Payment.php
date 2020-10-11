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

    public $total_remain = 0;
    public $total_paid = 0;

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

    public function setReaminAndPaid()
    {
        $payments = Payment::where('contract_id', $this->contract_id)
            ->where('remain', '>', 0)
            ->get();
        $this->total_remain = $payments->sum('remain');
        $this->total_paid = $payments->sum('paid');
    }

    public function getTotalRemain()
    {
        return $this->total_remain;
    }

    public function getTotalPaid()
    {
        return $this->total_paid;
    }
}