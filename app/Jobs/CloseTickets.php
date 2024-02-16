<?php

namespace App\Jobs;

use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CloseTickets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Obtener todos los tickets que no tienen respuesta en los últimos 7 días.
        $tickets = Ticket::where('ticket_status_id', 2)
            ->orWere('ticket_status_id', 1)
            ->where('updated_at', '<', now()->subDays(7))
            ->get();

        // Cerramos tickets
        foreach ($tickets as $ticket) {
            $ticket->ticket_status_id = 5;
            $ticket->update();
        }
    }

}
