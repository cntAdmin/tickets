<?php

namespace App\Http\Controllers;

use App\Mail\NewCommentMail;
use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Str;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req, Ticket $ticket)
    {
        $customAttributes = [
            'comment' => 'Comentario',
            'files' => 'Ficheros'
        ];
        $messages = [
            'required' => __(':attribute es un campo obligatorio.'),
            'numeric' => __(':attribute debe ser un id.'),
            'exists' => __(':attribute debe ser un id válido de la tabla "tickets".'),
            'string' => __(':attribute debe ser una cadena de caracteres.'),
            'array' => __(':attribute debe ser un array de ficheros.'),
            'size' => __(':attribute tiene un tamaño máximo de 25MB.'),
        ];
        $validator = Validator::make($req->all(), [
            'comment' => ['required', 'string'],
            'files' => ['array', 'nullable', 'max:25600']
        ], $messages, $customAttributes);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        
        try {
            $create_comment = Comment::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->user()->id ?? $ticket->user->id,
                'description' => $req->comment,
                '_token' => $this->createToken(),
            ]);
        } catch (\Throwable $th) {
            return $th;
        }

        $admin_users = \App\Models\User::role([1, 2, 3, 4])->pluck('id', 'id');
        $ticket_admin_comments = Ticket::where('tickets.id', $ticket->id)
            ->whereHas('comments.user', function (Builder $q) use ($admin_users) {
                $q->whereIn('users.id', $admin_users);
            })->withCount('comments')
            ->first();

        if (empty($ticket_admin_comments) || ($ticket_admin_comments && $ticket_admin_comments->comments_count == 1)) {
            $ticket->update([
                'ticket_status_id' => 2
            ]);
        }

        if ($req->file('files')) {
            foreach ($req->file('files') as $file) {
                $stored_file = Storage::disk('public')->put('media/' . now()->year . '/' . str_pad(now()->month, 2, '0', STR_PAD_LEFT), $file);
                $attachment = Attachment::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $stored_file
                ]);
                $create_comment->attachments()->save($attachment);
            }
        }

        return $create_comment
            ? response()->json(['success' => true, 'msg' => __('Comentario creado correctamente.')])
            : response()->json([
                'error' => true,
                'msg' =>  __('El comentario no se ha podido crear, por favor intentelo de nuevo mas tarde o contacte con el administrador.')
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket, Comment $comment)
    {
        // $this->authorize('comments.destroy');

        $comment->update([
            'deleted_by' => auth()->user()->id
        ]);

        $comment->attachments()->each(function ($attachment) {
            Storage::delete($attachment->path);
        });

        $deleted = $comment->delete();

        return $deleted
            ? response()->json(['success' => true, 'msg' => 'Commentario eliminado correctamente.'])
            : response()->json([
                'error' => true, 'msg' => 'El comentario no se ha podido borrar, pruebe de nuevo mas tarde o contacte con el administrador.'
            ]);
    }

    public function view_ticket_token(Comment $comment)
    {
        if ($comment->created_at->diffInMinutes(now()) > $comment->expires_in) {
            abort(404);
        }
        return redirect('/ver/incidencia/' . $comment->ticket->id);
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
