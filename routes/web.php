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
//     $admin_users = \App\Models\User::role([1, 2, 3, 4])->pluck('id', 'id');
//     $admin_comments = \App\Models\Ticket::where('tickets.id', 52)->whereHas('comments.user', function($q) use ($admin_users) {
//         $q->whereIn('users.id', $admin_users);
//     })->withCount('comments')->first();
    
//     dd($admin_comments->comments_count);

// });

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/{any}', 'SpaController@index')->where('any', '.*');
});