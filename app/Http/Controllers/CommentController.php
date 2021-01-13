<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Http\Request;
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
        $messages = [
            'required' => __(':attribute es un campo obligatorio.'),
            'numeric' => __(':attribute debe ser un id.'),
            'exists' => __(':attribute debe ser un id válido de la tabla "tickets".'),
            'string' => __(':attribute debe ser una cadena de caracteres.'),
        ];
        $validator = Validator::make($req->all(), [
            'description' => ['required', 'string'],
        ], $messages);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }

        $create_comment = Comment::create([
            'description' => $req->description
        ]);
         
        $ticket_assigned = $ticket->comments()->save($create_comment);
        $user_assigned = auth()->user->comments()->save($create_comment);

        return $ticket_assigned && $user_assigned
            ? response()->json(['success' => __('Comentario creado correctamente.')])
            : response()->json([
                'error' => __('El comentario no se ha podido crear, por favor intentelo de nuevo mas tarde o contacte con el administrador.')
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
    public function destroy(Comment $comment)
    {
        $this->authorize('comments.destroy');

        $comment->update([
            'deleted_by' => auth()->user()->id
        ]);
        $deleted = $comment->delete();

        return $deleted
            ? response()->json(['success' => 'Commentario eliminado correctamente.'])
            : response()->json([
                'error' => 'El comentario no se ha podido borrar, pruebe de nuevo mas tarde o contacte con el administrador.'
            ]);
    }
}
