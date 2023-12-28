<?php

namespace App\Observers;

use App\Mail\NewCommentMail;
use App\Mail\NewTicketMail;
use App\Models\Ticket;
use Illuminate\Support\Facades\Mail;

class TicketObserver
{
    public function created(Ticket $ticket)
    {
        if (env('APP_ENV') !== 'local') {
            $getCustomerMail = $ticket->customer;
            $getUserMail = $ticket->user;
            
            if($getCustomerMail->email === $getUserMail->email) {
                Mail::to($getCustomerMail->email)
                    // ->bcc('matias@costanetworks.es')
                    ->send(new NewTicketMail($ticket));
            } else {
                Mail::to($getCustomerMail->email)
                    // ->bcc('matias@costanetworks.es')
                    ->send(new NewTicketMail($ticket));
                Mail::to($getUserMail->email)
                    // ->bcc('matias@costanetworks.es')
                    ->send(new NewTicketMail($ticket));
            }
        }
    }
}
