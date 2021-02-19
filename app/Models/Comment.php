<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'description'
    ];

    protected $with = [
        'user', 'attachments'
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

    public function attachments()
    {
        return $this->morphToMany(\App\Models\Attachment::class, 'attachable');
    }

}
