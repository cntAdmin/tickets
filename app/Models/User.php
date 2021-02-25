<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use SendsPasswordResetEmails;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'username', 'phone', 'email', 'password', 'email_verified_at', 'remember_token', 'is_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $appends = ['active_status', 'fullname'];

    public function getActiveStatusAttribute() {
        return $this->attributes['is_active'] == 1 ? 'Activo' : 'Inactivo';
    }

    public function getFullnameAttribute() {
        return "{$this->attributes['name']} {$this->attributes['surname']}";
    }
    

    public function scopeFilterUsers(Builder $query) {
        $query->when(request()->input('name'), function(Builder $q, $name) {
            $q->where('name', 'LIKE', $name . '%');
        })->when(request()->input('surname'), function(Builder $q, $surname) {
            $q->where('surname', 'LIKE', $surname . '%');
        })->when(request()->input('username'), function(Builder $q, $username) {
            $q->where('username', 'LIKE', $username . '%');
        })->when(request()->input('email'), function(Builder $q, $email) {
            $q->where('email', 'LIKE', $email . '%');
        })->when(request()->input('phone'), function(Builder $q, $phone) {
            $q->where('phone', 'LIKE', $phone . '%');
        });
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id', 'id')->withCount('tickets');
    }

    public function tickets()
    {
        return $this->hasMany(\App\Models\Ticket::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class, 'user_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(\App\Models\Department::class, 'department_id', 'id');
    }

}
