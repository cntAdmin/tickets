<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    protected $fillable = [
        'name'
    ];

    public function brand()
    {
        return $this->belongsTo(\App\Models\Brand::class, 'brand_id', 'id');
    }
}
