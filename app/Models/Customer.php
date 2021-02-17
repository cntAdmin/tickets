<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'cif', 'custom_id', 'fiscal_name', 'comercial_name', 'phone', 'email', 'street', 'city', 'province', 'country', 'postcode',
        'shop', 'is_active', 'deleted_by'
    ];
    protected $appends = ['active_status'];

    public function getActiveStatusAttribute() {
        return $this->attributes['is_active'] == 1 ? 'Activo' : 'Inactivo';
    }
    
    public function users()
    {
        return $this->hasMany(\App\Models\User::class, 'customer_id', 'id');
    }

    public function tickets()
    {
        return $this->hasMany(\App\Models\Ticket::class, 'customer_id', 'id');
    }
}

