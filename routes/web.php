<?php

use App\Exports\TicketExport;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/testing', function (Request $req) {
//     switch ($req->type) {
//         case 'excel':
//             if(!Storage::exists('exports/excels')) {
//                 Storage::makeDirectory('exports/excels');
//             }

//             $filename = 'tickets-' . now()->toDateTimeString() . '.xlsx';
//             $storage = 'exports/excels/' . $filename;
//             $store = Excel::store(new TicketExport($req), $storage);
//             break;
//         case 'pdf':
//             if(!Storage::exists('exports/pdfs')) {
//                 Storage::makeDirectory('exports/pdfs');
//             }
//             $filename = 'tickets-' . now()->format('Y-m-d_H-i-s') . '.pdf';
//             $storage = 'exports/pdfs/' . $filename;
//             $tickets = Ticket::getTickets($req)->get();
//             $pdf = PDF::loadView('pdfs.tickets', ['tickets' => $tickets])
//                 ->setOptions([
//                     'defaultFont' => 'sans-serif',
//                     'debugCss' => true
//                 ]);
//             Storage::put($storage, $pdf->output());
//                     // ->save(storage_path() . '/' . $storage);
//             break;
//     }
//     $headers = [
//         'Content-Type' => 'application/*',
//     ];
    
//     return response()->download(Storage::path($storage), $filename , $headers);
// });

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/{any}', 'SpaController@index')->where('any', '.*');
});