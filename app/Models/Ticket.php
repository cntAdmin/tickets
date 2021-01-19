<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'custom_id', 'frame_id', 'plate', 'brand', 'model', 'subject', 'description', 'tests_done', 'ask_for', 'knowledge_base',
        'engine_type',
        // FOREIGN KEYS
        'customer_id', 'department_id', 'user_id', 'deleted_by', 'call_id', 'ticket_status_id'
    ];

    protected $casts = [
        'knowledge_base' => 'boolean',
    ];

    protected $with = [
        'comments', 'status', 'calls'
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

    public function calls()
    {
        return $this->hasMany(\App\Models\Call::class, 'ticket_id', 'id');
    }

}