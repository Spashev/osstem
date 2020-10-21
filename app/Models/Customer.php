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
        return $this->payments()->where('deadline', '<', Carbon::now()->format('Y-m-d'));
    }

    public function notifyPayments()
    {
        return $this->payments()->where('deadline', Carbon::now()->addDays(3)->format('Y-m-d'));
    }
}