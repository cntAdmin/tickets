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
// Route::get('/testing', function (Request $req) {

//     $ticket = App\Models\Comment::first();

//     return (new App\Mail\NewCommentMail($ticket))->render();
// });

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/{any}', 'SpaController@index')->where('any', '.*');
});
