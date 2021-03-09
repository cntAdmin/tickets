<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CustomerExport implements FromView, ShouldAutoSize
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
        $customers = Customer::filterCustomers()->get();
        return view('exports.customers')->with(['customers' => $customers]);
    }
}
