<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UserExport implements FromView, ShouldAutoSize
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
        $users = User::filterUsers()->get();
        return view('exports.users')->with(['users' => $users]);
    }
}
