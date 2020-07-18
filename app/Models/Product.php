<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
