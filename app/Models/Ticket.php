<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'frame_id', 'plate', 'brand', 'model', 'subject', 'description', 'tests_done', 'ask_for', 'knowledge_base', 'status'
    ];

    public function department()
    {
        return $this->belongsTo(\App\Models\Department::class, 'department_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id', 'id');
    }

    public function contact()
    {
        return $this->belongsTo(\App\Models\Contact::class, 'contact_id', 'id');
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
    
}