<?php

namespace App\Models;

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
}