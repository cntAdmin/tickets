<?php

namespace App\Observers;

use App\Mail\NewCommentMail;
use App\Models\Comment;
use Illuminate\Support\Facades\Mail;

class CommentObserver
{
    /**
     * Handle the comment "created" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
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

    /**
     * Handle the comment "updated" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function updated(Comment $comment)
    {
        //
    }

    /**
     * Handle the comment "deleted" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        //
    }

    /**
     * Handle the comment "restored" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function restored(Comment $comment)
    {
        //
    }

    /**
     * Handle the comment "force deleted" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function forceDeleted(Comment $comment)
    {
        //
    }
}
