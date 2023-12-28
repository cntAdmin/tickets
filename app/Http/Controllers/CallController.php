<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\Ticket;
use App\Exports\CallExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class CallController extends Controller
{
    public function index(Request $req)
    {
        if($req->ajax()) {
            $calls = Call::filterCalls()
                ->orderBy('start', 'DESC')
                ->with(['customer', 'ticket'])
                ->paginate();
            
            
            return response()->json([
                'success' => true, 
                'calls' => $calls
            ]);
        }
    }

    public function get_calls_count(Request $req) 
    {
        if($req->type !== 'internal'){
            $calls = Call::filterCalls()->get();
        }
        $incoming = clone $calls;
        $outgoing = clone $calls;
        $incoming_count = $incoming->where('is_incoming', 1)->where('is_outgoing', 0);
        $outgoing_count = $outgoing->where('is_incoming', 0)->where('is_outgoing', 1);

        return response()->json([
            'total_count' => $calls->count() ?? 0,
            'incoming_count' => $incoming_count->count() ?? 0,
            'outgoing_count' => $outgoing_count->count() ?? 0,
            'incall_time_count' => $calls->sum('duration') ?? 0,
        ]);
    }

    public function get_all_calls(Request $req) 
    {
        $calls = Call::paginate();

        return response()->json([
            'success' => true,
            'calls' => $calls
        ]);
    }

    public function toggle_call_ticket(Call $call, Ticket $ticket, Request $req) 
    {
        if($req->toggle) {
            $call->ticket()->associate($ticket->id);
            $call->customer()->associate($ticket->customer->id);
        } else {
            $call->ticket()->dissociate($ticket->id);
            $call->customer()->dissociate($ticket->customer->id);
        }
        $call->save();

        return response()->json([
            'success' => true,
            'msg' => 'Llamada ' . ($req->toggle ? 'asignada' : 'desasignada') . ' correctamente.'
        ]);
    }

    public function asignable_calls(Request $req) 
    {
        $calls = Call::filterCalls()->orderBy('start', 'DESC')->paginate();

        return response()->json([
            'success' => true,
            'calls' => $calls
        ]);
    }

    public function export_calls(Request $req)
    {
        switch ($req->type) {
            case 'excel':
                if (!Storage::exists('exports/excels')) {
                    Storage::makeDirectory('exports/excels');
                }
                
                $filename = 'calls-' . now()->toDateTimeString() . '.xlsx';
                $storage = 'exports/excels/' . $filename;
                $store = Excel::store(new CallExport(), $storage);
                break;
            case 'pdf':
                if (!Storage::exists('exports/pdfs')) {
                    Storage::makeDirectory('exports/pdfs');
                }
                $filename = 'calls-' . now()->format('Y-m-d_H-i-s') . '.pdf';
                $storage = 'exports/pdfs/' . $filename;
                $calls = Call::filterCalls()->get();
                $pdf = PDF::loadView('exports.calls', ['calls' => $calls])
                    ->setPaper('a4', 'landscape')
                    ->setOptions([
                        'defaultFont' => 'sans-serif',
                    ])->save('storage/' . $storage);
                break;
        }
        $headers = [
            'Content-Type' => 'application/*',
        ];

        return response()->download(Storage::path($storage), $filename, $headers);
    }
}
