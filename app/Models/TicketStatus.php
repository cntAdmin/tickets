<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model
{
    protected $fillable = [
        'name'
    ];

    public function tickets()
    {
        return $this->hasMany(\App\Models\Ticket::class, 'ticket_status_id', 'id');
    }

    
}
