<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'id', 'name', 'deleted_by'
    ];

    public function models(): HasMany
    {
        return $this->hasMany(\App\Models\CarModel::class, 'brand_id', 'id');
    }

    /**
     * Get all of the tickets for the Brand
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'brand_id', 'id');
    }
}
