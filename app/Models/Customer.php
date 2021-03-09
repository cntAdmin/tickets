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

    public function scopeFilterCustomers(Builder $builder)
    {
        return $builder->when(request()->input('custom_id'), function(Builder $q, $custom_id){
            $q->where('custom_id', 'LIKE', $custom_id . '%');
        })->when(request()->input('cif'), function(Builder $q, $cif){
            $q->where('cif', 'LIKE', $cif . '%');
        })->when(request()->input('fiscal_name'), function(Builder $q, $fiscal_name){
            $q->where('fiscal_name', 'LIKE', '%' . $fiscal_name . '%');
        })->when(request()->input('comercial_name'), function(Builder $q, $comercial_name){
            $q->where('comercial_name', 'LIKE', '%' . $comercial_name . '%');
        })->when(request()->input('phone'), function(Builder $q, $phone){
            $q->where('phone_1', 'LIKE', $phone . '%')
                ->orWhere('phone_2', 'LIKE', $phone . '%')
                ->orWhere('phone_3', 'LIKE', $phone . '%');
        })->when(request()->input('email'), function(Builder $q, $email){
            $q->where('email', 'LIKE', $email . '%');
        })->when(request()->input('street'), function(Builder $q, $street){
            $q->where('street', 'LIKE', $street . '%');
        })->when(request()->input('town'), function(Builder $q, $town){
            $q->where('town', 'LIKE', $town . '%');
        })->when(request()->input('city'), function(Builder $q, $city){
            $q->where('city', 'LIKE', $city . '%');
        })->when(request()->input('country'), function(Builder $q, $country){
            $q->where('country', 'LIKE', $country . '%');
        })->when(request()->input('postcode'), function(Builder $q, $postcode){
            $q->where('postcode', 'LIKE', $postcode . '%');
        })->when(request()->input('shop'), function(Builder $q, $shop){
            $q->where('shop', 'LIKE', $shop . '%');
        })->when(request()->input('is_active'), function(Builder $q, $is_active){
            $q->where('is_active', $is_active);
        });
    }

}

