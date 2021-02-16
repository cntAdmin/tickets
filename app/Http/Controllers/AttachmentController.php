<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{

    /**
     * * Estructura de directorios YYYY/MM/DD/ticket_id
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $files = Attachment::getAttachments($req)->paginate();

        return response()->json([
            'success' => true,
            'files' => $files
        ]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function show(Attachment $attachment)
    {
        return response()->download(public_path('storage/') . $attachment->path, $attachment->name, ['Content-Type: image/png']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function edit(Attachment $attachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attachment $attachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * * BORRAR TODOS QUE NO ESTEN CON FAQS ACTIVADO
     * @param  \App\Models\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attachment $attachment)
    {
        return $attachment;
        //
    }

    public function download(Comment $comment, Attachment $attachment) {
        // $this->authorize('downloads.comment.files', $comment);

        if($comment->ticket->whereHas('comments', function(Builder $q) {
            $q->where('comments.user_id', auth()->user()->id);
        })->exists()) {
            return Storage::download($attachment->path, $attachment->name);
        }
    }

    public function deleteAll(Request $req) {
        // getAttachments(Request $req, $type = null)
        $filesDeleted = Attachment::getAttachments($req, 'toDelete')->delete();

        return response()->json([
            'success' => true,
            'msg' => $filesDeleted . ' ' . __('Ficheros eliminados correctamente')
        ]);
    }

    public function deleteSelected(Request $req) {
        $files = Attachment::whereIn('id', $req->selected)
            ->whereHas('comments.ticket', function(Builder $q) {
                $q->where('tickets.knowledge_base', 0);
            });
        if($files->count() > 0) {
            $files->delete();
            return response()->json([
                'success' => true,
                'msg' => $files . ' ' . __('Ficheros eliminados correctamente')
            ]);
        }
        return response()->json([
            'error' => true,
            'msg' => __('Los ficheros seleccionados estan en FAQS, por favor, si desea eliminarlos quitelos del ticket relacionado')
        ]);
        return $files;
    }
}
