<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Manager extends Model
{
    use Sortable;

    protected $guarded = [];

    public $timestamps = false;

    public $sortable = ['name', 'in_charge'];

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}