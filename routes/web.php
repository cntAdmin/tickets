<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::resource('user', 'UserController');
    Route::resource('media', 'AttachmentController')->parameter('media', 'attachment');
    Route::resource('department', 'DepartmentController');
    Route::resource('engineType', 'EngineTypeController');
    Route::resource('ticket', 'TicketController');
    Route::resource('ticket.comments', 'CommentController');
    Route::resource('customer', 'CustomerController');

    // VUE GETTERS
    Route::get('get_ticket_counter/{ticket_status?}', 'TicketController@get_ticket_counter');
    Route::get('get_all_departments', 'DepartmentController@get_all_departments');
    Route::get('get_all_tickets', function() {
        return \App\Models\TicketStatus::all();
    });

    Route::get('users/{id}', function ($id) {
        
    });
});

Route::get('/home', 'HomeController@index')->name('home');