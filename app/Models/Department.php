<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name', 'code'
    ];

    public function attributes()
    {
        return [
            'name' => __('Nombre'),
            'code' => __('Código'),
        ];
    }
    
    public function users()
    {
        return $this->hasMany(\App\Models\User::class, 'department_id', 'id');
    }

    public function tickets()
    {
        return $this->hasMany(\App\Models\Tickets::class, 'department_id', 'id');
    }

}
