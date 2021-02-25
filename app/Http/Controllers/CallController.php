<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\Customer;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if($req->ajax()) {
            $calls = Call::getCalls($req)->with('customer')
                ->paginate();
            
            
            return response()->json([
                'success' => true, 
                'calls' => $calls
            ]);
        }
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
     * @param  \App\Models\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function show(Call $call)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function edit(Call $call)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Call $call)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function destroy(Call $call)
    {
        //
    }

    public function get_calls_count(Request $req) {
        if($req->type !== 'internal'){
            $calls = Call::getCalls($req)->get();
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

    public function get_all_calls(Request $req) {
        $calls = Call::paginate();


    return response()->json([
        'success' => true,
        'calls' => $calls
    ]);

    }

    public function toggle_call_ticket(Call $call, Ticket $ticket, Request $req) {
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

    public function asignable_calls(Request $req) {
        $req->type = 'finder';
        $calls = Call::getCalls($req)->paginate();

        return response()->json([
            'success' => true,
            'calls' => $calls
        ]);
    }
}
