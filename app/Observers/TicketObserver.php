<?php

namespace App\Observers;

use App\Mail\NewCommentMail;
use App\Mail\NewTicketMail;
use App\Models\Ticket;
use Illuminate\Support\Facades\Mail;

class TicketObserver
{
    /**
     * Handle the ticket "created" event.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return void
     */
    public function created(Ticket $ticket)
    {
        if (env('APP_ENV') !== 'local') {
            $getCustomerMail = $ticket->customer;
            $getUserMail = $ticket->user;
            
            if($getCustomerMail->email === $getUserMail->email) {
                Mail::to($getCustomerMail->email)
                    // ->bcc('nacho@costanetworks.es')
                    ->send(new NewTicketMail($ticket));
            } else {
                Mail::to($getCustomerMail->email)
                    // ->bcc('nacho@costanetworks.es')
                    ->send(new NewTicketMail($ticket));
                Mail::to($getUserMail->email)
                    // ->bcc('nacho@costanetworks.es')
                    ->send(new NewTicketMail($ticket));
            }
        }
    }

    /**
     * Handle the ticket "updated" event.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return void
     */
    public function updated(Ticket $ticket)
    {
        //
    }

    /**
     * Handle the ticket "deleted" event.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return void
     */
    public function deleted(Ticket $ticket)
    {
        //
    }

    /**
     * Handle the ticket "restored" event.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return void
     */
    public function restored(Ticket $ticket)
    {
        //
    }

    /**
     * Handle the ticket "force deleted" event.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return void
     */
    public function forceDeleted(Ticket $ticket)
    {
        //
    }
}
