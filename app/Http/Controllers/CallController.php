<?php

namespace App\Http\Controllers;

use App\Models\Call;
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

    public function get_all_calls(Request $req) {
        $calls = Call::when($req->ticket_id, function(Builder $q, $ticket_id){
                $q->where(function($q2) use($ticket_id){
                    $q2->whereNull('ticket_id')->orWhere('ticket_id', $ticket_id);
                });
            }, function($q){
                $q->whereNull('ticket_id');
            })->where('disposition', 'ANSWERED')
            ->when($req->phone_number, function(Builder $q, $phone_number){
                $q->where(function($q2) use ($phone_number){
                    $q2->where('src', 'LIKE', '%' . $phone_number . '%')->orWhere('dst', 'LIKE', '%' . $phone_number . '%');
                });
            })->when($req->date, function(Builder $q, $date){
                $q->whereDate('start', $date);
            }, function($q){
                $q->whereBetween('start', [Carbon::parse('first day of this month')->toDateString(), now()]);
            })
            ->orderBy('ticket_id', 'DESC')
            ->limit(15)->get();


        return response()->json([
            'calls' => $calls
        ]);
    }
}
