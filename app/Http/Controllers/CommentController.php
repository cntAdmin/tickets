<?php

namespace App\Http\Controllers;

use App\Mail\NewCommentMail;
use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        $create_comment = Comment::create([
            'description' => $req->comment
        ]);
        
        $ticket_assigned = $ticket->comments()->save($create_comment);
        $user_assigned = $create_comment->user()->associate(auth()->user()->id);
        $create_comment->save();
        if($req->file('files')) {
            foreach ($req->file('files') as $file) {
                $stored_file = Storage::disk('public')->put('media', $file);
                $attachment = Attachment::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $stored_file
                    ]);
                $create_comment->attachments()->save($attachment);
            }
        }
        if(!App::environment('local')) {
            // Mail::to(auth()->user())->send(new NewCommentMail);
        }

        return $ticket_assigned && $user_assigned
            ? response()->json(['success' => true, 'msg' => __('Comentario creado correctamente.') ])
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

        $comment->attachments()->each(function($attachment) {
            Storage::delete($attachment->path);
        });

        $deleted = $comment->delete();

        return $deleted
            ? response()->json(['success' => true, 'msg' => 'Commentario eliminado correctamente.'])
            : response()->json([
                'error' => true, 'msg' => 'El comentario no se ha podido borrar, pruebe de nuevo mas tarde o contacte con el administrador.'
            ]);
    }
}
