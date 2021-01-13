<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'description'
    ];

    protected $with = [
        'user'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    public function ticket()
    {
        return $this->belongsTo(\App\Models\Ticket::class, 'ticket_id', 'id');
    }

    public function deleted_by()
    {
        return $this->belongsTo(\App\Models\User::class, 'deleted_by', 'id');
    }

}
