<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Customer extends Model
{
    use Sortable;

    protected $guarded = [];

    public $timestamps = false;

    public $sortable = ['name', 'customer_id', 'region'];

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function deadlinePayments()
    {
        return $this->payments()->where('remain', '>', 0)->where('deadline', '<', Carbon::now()->format('Y-m-d'))->where('sms_status', 'on');
    }

    public function notifyPayments()
    {
        $end = new Carbon('last day of this month');
        return $this->payments()->where('deadline', '<', $end)->where('remain', '>', 0)->where('sms_status', 'on');
    }

    public function customerPayments()
    {
        return $this->payments()->where('deadline', '<', Carbon::now()->format('Y-m-d'))->where('remain', '>', 0)->where('sms_status', 'on')->get()->unique('customer_id');
    }

    public function getCustomerPaid()
    {
        return $this->payments()->where('sms_status', 'on')
            ->where('remain', '>', 0)
            ->where('deadline', '<', Carbon::now()->format('Y-m-d'))
            ->get()->sum('paid');
    }

    public function getCustomerRemain()
    {
        return $this->payments()->where('sms_status', 'on')
            ->where('remain', '>', 0)
            ->where('deadline', '<', Carbon::now()->format('Y-m-d'))
            ->get()->sum('remain');
    }
}