<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'cif', 'fiscal_name', 'comercial_name', 'phone', 'email', 'street', 'town', 'city', 'country', 'postcode', 'shop', 'is_active'
    ];

    public function contacts()
    {
        return $this->hasMany(\App\Models\Contact::class, 'customer_id', 'id');
    }

    public function tickets()
    {
        return $this->hasMany(\App\Models\Ticket::class, 'customer_id', 'id');
    }
}

