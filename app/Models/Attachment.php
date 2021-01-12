<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'name', 'path', 'deleted_by'
    ];
    
    
    public function tickets()
    {
        return $this->belongsToMany(\App\Models\Ticket::class, 'attachment_ticket', 'attachment_id', 'ticket_id');
    }
}
