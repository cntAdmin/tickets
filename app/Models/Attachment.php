<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Attachment extends Model
{
    protected $fillable = [
        'name', 'path', 'deleted_by'
    ];
    
    public function tickets()
    {
        return $this->morphedByMany(\App\Models\Ticket::class, 'attachable');
    }
    public function comments()
    {
        return $this->morphedByMany(\App\Models\Comment::class, 'attachable');
    }

    public static function getAttachments(Request $req, $type = null) {
        return Attachment::when($type == 'toDelete', function(Builder $q){
            $q->whereHas('comments.ticket', function(Builder $q2) {
                $q2->where('tickets.knowledge_base', 0);
            });
        })->when($req->ticket_id, function(Builder $q, $ticket_id){
            $q->whereHas('comments.ticket', function(Builder $q2) use ($ticket_id) {
                $q2->where('tickets.custom_id', 'LIKE', $ticket_id . '%');
            });
        })->when($req->subject, function(Builder $q, $subject){
            $q->whereHas('comments.ticket', function(Builder $q2) use ($subject) {
                $q2->where('tickets.subject', 'LIKE', $subject . '%');
            });
        })->when($req->in_faqs, function(Builder $q, $knowledge_base){
            $q->whereHas('comments.ticket', function(Builder $q2) use ($knowledge_base) {
                $q2->where('tickets.knowledge_base', 'LIKE', $knowledge_base . '%');
            });
        })->with(['comments.ticket']);
    }
}
