<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'frame_id', 'plate', 'brand', 'model', 'subject', 'description', 'tests_done', 'ask_for', 'knowledge_base', 'status'
    ];

    protected $casts = [
        'knowledge_base' => 'boolean'
    ];

    protected $with = [
        'comments', 'status', 'call'
    ];

    public function department()
    {
        return $this->belongsTo(\App\Models\Department::class, 'department_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    public function engine_type()
    {
        return $this->belongsTo(\App\Models\EngineType::class, 'engine_type_id', 'id');
    }

    public function deleted_by()
    {
        return $this->belongsTo(\App\Models\User::class, 'deleted_by', 'id');
    }

    public function attachments()
    {
        return $this->belongsToMany(\App\Models\Attachment::class, 'attachment_ticket', 'attachment_id', 'ticket_id');
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class, 'ticket_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(\App\Models\TicketStatus::class, 'ticket_status_id', 'id');
    }

    public function call()
    {
        return $this->belongsTo(\App\Models\Mtcdr\Call::class, 'call_id', 'id');
    }
    
}