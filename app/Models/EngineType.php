<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EngineType extends Model
{
    protected $fillable = [
        'name'
    ];

    public function tickets()
    {
        return $this->hasMany(\App\Models\Ticket::class, 'engine_type_id', 'id');
    }

}
