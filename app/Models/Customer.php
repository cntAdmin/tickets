<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'cif', 'fiscal_name', 'comercial_name', 'phone', 'email', 'street', 'town', 'city', 'country', 'postcode', 'shop', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function users()
    {
        return $this->hasMany(\App\Models\User::class, 'customer_id', 'id');
    }

    public function tickets()
    {
        return $this->hasMany(\App\Models\Ticket::class, 'customer_id', 'id');
    }
}

