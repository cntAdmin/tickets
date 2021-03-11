<?php

namespace App\Exports;

use App\Models\Call;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;

class CallExport implements FromView, ShouldAutoSize {
    use Exportable;

    /**
    * @return \Illuminate\Contracts\View\View
    */
    public function view(): View
    {
        $calls = Call::filterCalls()->with(['customer', 'ticket'])->get();
        return view('exports.calls')->with(['calls' => $calls]);
    }
}
