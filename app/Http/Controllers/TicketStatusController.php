<?php

namespace App\Http\Controllers;

use Str;
use Carbon\Carbon;
use App\Models\Ticket;
use App\Models\Comment;
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
        // Estado => Resuelto
        if($ticketStatus->id === 4) {
            $ticket_created_at = Carbon::parse($ticket->created_at);
            $diff_in_minutes = $ticket_created_at->diffInMinutes(Carbon::now());

            $ticket->update([
                'answered' => 1,
                'resolution_time' => $diff_in_minutes
            ]);

            // creamos un comentario informando que el ticket se ha resuelto
            Comment::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->user()->id ?? $ticket->user->id,
                'description' => 'Ticket Resuelto.',
                '_token' => $this->createToken(),
            ]);
        }
        $ticket->status()->associate($ticketStatus);
        $ticket->save();

        return response()->json([
            'success' => true,
            'ticket' => $ticket
        ]);
    }

    public function createToken()
    {
        $token = Str::uuid();
        $exists = Comment::where('_token', $token)->first();

        if(!$exists) {
            return $token;
        } else {
            $this->createToken();
        }
    }
}
