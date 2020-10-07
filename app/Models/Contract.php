<?php

namespace App\Models;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Contract extends Model
{
    use Sortable;

    protected $guarded = [];

    public $timestamps = false;

    public $sortable = ['contract_no'];

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