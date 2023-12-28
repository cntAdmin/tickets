<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Comment;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Builder;

class AttachmentController extends Controller
{
    /**
     * * Estructura de directorios YYYY/MM/DD/ticket_id
     */
    public function index(Request $req)
    {
        $files = Attachment::getAttachments($req)->paginate();

        return response()->json([
            'success' => true,
            'files' => $files
        ]);
    }

    public function show(Attachment $attachment)
    {
        return response()->download(public_path('storage/') . $attachment->path, $attachment->name, ['Content-Type: image/png']);
    }

    public function destroy(Attachment $attachment)
    {
        return $attachment;
    }

    public function download_attachment_comment(Comment $comment, Attachment $attachment, Request $req) 
    {
        // $this->authorize('downloads.comment.files', $comment);

        return Storage::download($attachment->path, $attachment->name);
    }

    public function download_attachment_ticket(Ticket $ticket, Attachment $attachment) 
    {
        // $this->authorize('downloads.comment.files', $comment);

        return Storage::download($attachment->path, $attachment->name);

    }

    public function deleteAll(Request $req) 
    {
        $filesDeleted = Attachment::getAttachments($req, 'toDelete')->delete();

        return response()->json([
            'success' => true,
            'msg' => $filesDeleted . ' ' . __('Ficheros eliminados correctamente')
        ]);
    }

    public function deleteSelected(Request $req) 
    {
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

    public function get_files_counters() 
    {
        $inFaqs = Attachment::whereHas('comments.ticket', function(Builder $q) {
            $q->where('tickets.knowledge_base', 1);
        })->count();

        return response()->json([
            'success' => true,
            'inFaqs' => $inFaqs
        ]);
    }
}
