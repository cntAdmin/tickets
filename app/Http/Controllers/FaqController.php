<?php

namespace App\Http\Controllers;

use App\Scopes\RoleTicketFilterScope;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class FaqController extends Controller
{
    public function index(Request $req)
    {
        if($req->ajax()) {
            $faqs = Ticket::withoutGlobalScope(RoleTicketFilterScope::class)->filterTickets('faqs')->paginate();

            return response()->json([
                'success' => true,
                'faqs' => $faqs
            ]);
        }
    }
}
