<?php

namespace App\Jobs;

use App\Models\Call;
use App\Models\Mtcdr\AstCdr;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetCalls implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // GET LAST ID FROM TICKET DB
        $last_call = Call::orderBy('id', 'DESC')->first() ? Call::orderBy('id', 'DESC')->first() : 0 ;
        // GET CALLS FROM LAST ID
        $get_calls = AstCdr::select()->where('id', '>', $last_call)
            ->where('customer_id', env('CUSTOMER_ID', 10000))
            ->whereBetween('start', [Carbon::make('first day of this month'), now()])
            ->get();

        foreach ($get_calls as $call) {
            $insert_calls[] = [
                'id' => $call->id,
                'customer_id' => $call->customer_id,
                'src' => $call->src,
                'dst' => $call->dst,
                'dcontext' => $call->dsdcontext,
                'clid' => $call->clid,
                'channel' => $call->channel,
                'dstchannel' => $call->dstchannel,
                'lastapp' => $call->lastapp,
                'lastdata' => $call->lastdata,
                'start' => $call->start,
                'answer' => $call->answer,
                'end' => $call->end,
                'duration' => $call->duration,
                'billsec' => $call->billsec,
                'disposition' => $call->disposition,
                'userfield' => $call->userfield,
                'uniquefield' => $call->uniquefield,
                'linkedid' => $call->linkedid,
                'is_incoming' => $call->is_incoming,
                'is_outgoing' => $call->is_outgoing,
                'is_to_billing' => $call->is_to_billing,
                'is_recorded' => $call->is_recorded,
                'rating_status' => $call->rating_status,
                'src_extension' => $call->src_extension,
                'dst_extension' => $call->dst_extension,
                'src_number' => $call->src_number,
                'dst_number' => $call->dst_number,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        if(isset($insert_calls)) {
            Call::insert($insert_calls);
        }

    }
}
