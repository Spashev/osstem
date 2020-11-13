<?php

namespace App\Models;

use Carbon\Carbon;
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
            ->where('deadline', '<', Carbon::now()->format('Y-m-d'))
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

    public function getPaid()
    {
        $payments = Payment::where('customer_id', $this->customer_id)
            ->where('remain', '>', 0)
            ->where('deadline', '<=', Carbon::now()->format('Y-m-d'))
            ->get();
        $payments = $payments->filter(function ($value, $key) {
            return $value->remain > 0 and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
        });
        return  $payments->sum('paid');
    }

    public function getRemain()
    {
        $payments = Payment::where('customer_id', $this->customer_id)
            ->where('remain', '>', 0)
            ->where('deadline', '<=', Carbon::now()->format('Y-m-d'))
            ->get();
        $payments = $payments->filter(function ($value, $key) {
            return $value->remain > 0 and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
        });
        return  $payments->sum('remain');
    }

    public function getCustomerPaid()
    {   
        $end = new Carbon('last day of this month');
        $payments = Payment::where('customer_id', $this->customer_id)
            ->where('remain', '>', 0)
            ->where('deadline', '<', $end)
            ->get();
        $payments = $payments->filter(function ($value, $key) {
            return $value->remain > 0 and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
        });
        return  $payments->sum('paid');
    }

    public function getCustomerRemain()
    {
        $end = new Carbon('last day of this month');
        $payments = Payment::where('customer_id', $this->customer_id)
            ->where('remain', '>', 0)
            ->where('deadline', '<', $end)
            ->get();
        $payments = $payments->filter(function ($value, $key) {
            return $value->remain > 0 and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
        });
        return  $payments->sum('remain');
    }
}