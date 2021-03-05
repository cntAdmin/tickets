<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Customer extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'cif', 'custom_id', 'fiscal_name', 'comercial_name', 'phone_1', 'phone_2', 'phone_3', 'email', 'street', 'city', 'province', 'country', 'postcode',
        'shop', 'is_active', 'deleted_by'
    ];
    protected $appends = ['active_status'];

    public function getActiveStatusAttribute() {
        return $this->attributes['is_active'] == 1 ? 'Activo' : 'Inactivo';
    }
    

    public function users(): HasMany
    {
        return $this->hasMany(\App\Models\User::class, 'customer_id', 'id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(\App\Models\Ticket::class, 'customer_id', 'id');
    }

    /**
     * Get the contacts that owns the Customer
     *
     * @return \Illuminate\Support\Collection
     */
    public function contacts(): Collection
    {
        return $this->users()->whereHas('roles', function(Builder $q){
            $q->where('roles.id', 6);
        })->with(['roles'])->get();
    }
}

