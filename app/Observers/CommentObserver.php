<?php

namespace App\Observers;

use App\Models\Comment;
use App\Mail\NewCommentMail;
use Illuminate\Support\Facades\Mail;

class CommentObserver
{
    public function created(Comment $comment)
    {
        // if (env('APP_ENV') !== 'local' && $comment->user->roles[0]->id <= 4) {
        //     $getCustomerMail = $comment->ticket->customer;
        //     $getUserMail = $comment->ticket->user;

        //     if ($getCustomerMail->email === $getUserMail->email) {
        //         Mail::to($getCustomerMail->email)->send(new NewCommentMail($comment));
        //     } else {
        //         Mail::to($getCustomerMail->email)->send(new NewCommentMail($comment));
        //         Mail::to($getUserMail->email)->send(new NewCommentMail($comment));
        //     }
        // }
        
        // UPDATE TICKET ANSWERED: IF COMMENTED BY ADMINS SET AS FALSE ELSE SET AS TRUE
        $comment->ticket->update([
            'answered' => $comment->user->roles[0]->id <= 4 ? true : false
        ]);
    }
}
