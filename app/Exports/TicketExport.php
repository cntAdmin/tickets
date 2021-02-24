<?php

namespace App\Exports;

use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TicketExport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $req;

    public function __construct(Request $req)
    {
        $this->req = $req;
    }

    /**
    * @return \Illuminate\Contracts\View\View
    */
    public function view(): View
    {
        $tickets = Ticket::filterTickets()->get();
        return view('exports.excels.tickets')->with(['tickets' => $tickets]);
    }

}
