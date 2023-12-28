<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketStatus;
use Illuminate\Http\Request;

class TicketStatusController extends Controller
{
    public function get_all_ticket_statuses(Request $req)
    {
        return response()->json(['success' => true, 'ticket_statuses' => \App\Models\TicketStatus::all()]);
    }

    public function change_status(Ticket $ticket, TicketStatus $ticketStatus) 
    {
        if($ticketStatus->id === 4) {
            $ticket->update(['answered' => 1]);
        }
        $ticket->status()->associate($ticketStatus);
        $ticket->save();

        return response()->json([
            'success' => true,
            'ticket' => $ticket
        ]);
    }
}
