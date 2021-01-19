<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    protected $fillable = [
        'id', 'customer_id', 'ticket_id', 'src', 'dst', 'dcontext', 'clid', 'channel', 'dstchannel', 'lastapp', 'lastdata', 'start', 
        'answer', 'end', 'duration', 'billsec', 'disposition', 'userfield', 'uniquefield', 'linkedid', 'is_incoming', 'is_outgoing',
        'is_to_billing', 'is_recorded', 'rating_status', 'src_extension', 'dst_extension', 'src_number', 'dst_number'
    ];
    
    public function ticket()
    {
        return $this->belongsTo(\App\Models\Ticket::class, 'ticket_id', 'id');
    }
}
