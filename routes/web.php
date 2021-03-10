<?php

use App\Exports\TicketExport;
use App\Imports\TicketsImport;
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
// Route::get('testing1', 'UserController@export_users');
Route::get('/testing', function (Request $req) {
    $ticket = Ticket::find(74);
    $admin_users = \App\Models\User::role([1, 2, 3, 4])->pluck('id', 'id');
    $ticket_admin_comments = Ticket::where('tickets.id', $ticket->id)
        ->whereHas('comments.user', function ($q) use ($admin_users) {
            $q->whereIn('users.id', $admin_users);
        })->withCount('comments')
        ->first();
        
    if (empty($ticket_admin_comments) || ($ticket_admin_comments && $ticket_admin_comments->count() <= 0)) {
        $ticket->update([
            'ticket_status_id' => 2
        ]);
    }
    dd("post");

});

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/{any}', 'SpaController@index')->where('any', '.*');
});
