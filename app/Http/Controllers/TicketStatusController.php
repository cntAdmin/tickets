<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketStatus;
use Carbon\Carbon;
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
            // $ticket->update(['answered' => 1]);
            $ticket_created_at = Carbon::parse($ticket->created_at);
            $diff_in_minutes = $ticket_created_at->diffInMinutes(Carbon::now());

            $ticket->update([
                'answered' => 1,
                'resolution_time' => $diff_in_minutes
            ]);
        }
        $ticket->status()->associate($ticketStatus);
        $ticket->save();

        return response()->json([
            'success' => true,
            'ticket' => $ticket
        ]);
    }
}
