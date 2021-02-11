<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'brand_id', 'name'
    ];

    public function models()
    {
        return $this->hasMany(\App\Models\CarModel::class, 'brand_id', 'id');
    }
}
