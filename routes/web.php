<?php

use App\Exports\TicketExport;
use App\Imports\TicketsImport;
use App\Models\Call;
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
Route::get('/testing', 'TestController@tests');


Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/ver/{comment:_token}', 'CommentController@view_ticket_token');
Route::get('/ver/incidencia/{ticket}', function (Ticket $ticket) {
    // dd($ticket->load(['customer', 'user', 'department', 'brand', 'car_model']));
    return view('tickets.view')->withTicket($ticket->load(['customer', 'user', 'department', 'brand', 'car_model']));
});
Route::post('/api/ticket/{ticket}/comment', 'CommentController@store')->name('ticket.comment.store');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/{any}', 'SpaController@index')->where('any', '.*');
    Route::get('/test', 'TestController@tests');
});
