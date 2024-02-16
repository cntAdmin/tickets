<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Builder;
use Carbon\CarbonInterval;

class TestController extends Controller
{

    private $req;

    public function tests()
    {
        $tickets = Ticket::where('id',1517)->get();
        
        foreach ($tickets as $ticket) {
            $created_at = Carbon::parse($ticket->created_at);
            $diff_in_minutes = $created_at->diffInMinutes(Carbon::now());

            $human_format = CarbonInterval::minutes($diff_in_minutes)->cascade()->forHumans();
            dd($diff_in_minutes, $human_format);

        }

        // $field_name = '';
        // $brands = Brand::when($field_name, function(Builder $q, $name) {
        //     $q->where('name', 'LIKE', '%'. $name . '%');
        // })->withCount('models')
        // ->withCount(['tickets' => function ($query) {
        //         $query->where('ticket_status_id', '<', 6);
        //     }
        // ])->limit(5)->get();

        $incidenciasPorAgente = DB::table('tickets')
        ->join('users','users.id', '=', 'tickets.assigned_to')
        ->select([
            DB::raw('count(tickets.assigned_to) as total'), 
            DB::raw('sum(tickets.resolution_time) as resolution_time'), 
            DB::raw('users.name as name')
        ])->where('ticket_status_id', 4)->groupBy('name')->orderBy('total', 'desc')->get();
        
        dd($incidenciasPorAgente);
        // $agents = User::whereHas('assigned_to')->get();
        // $agents->each(function($ag) use (&$total_tickets, &$total_min, &$agent_tickets) {
        //     $agent_tickets = Ticket::where('assigned_to', $ag->id)->where('ticket_status_id',4)->get();
        //     // dd($agent_tickets);
        //     ->select([
        //         DB::raw('count(tickets.assigned_to) as total'), 
        //         DB::raw('users.name as name')
        //     ])->groupBy('name')->orderBy('total', 'desc')->get();
        // });

    }

}