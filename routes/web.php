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
    Route::resource('customer.ticket', 'CustomerTicketController');

    // VUE GETTERS
        // TICKETS
        Route::get('get_ticket_counter/{ticket_status?}', 'TicketController@get_ticket_counter');
        Route::get('/get_all_tickets', 'DepartmentController@get_all_departments');
        Route::get('/get_all_ticket_statuses', 'TicketStatusController@get_all_ticket_statuses');
        
        // CREATE
        Route::get('/get_all_users', 'UserController@get_all_users');
        Route::get('/get_all_customers', 'CustomerController@get_all_customers');
        Route::get('/get_all_departments', 'DepartmentController@get_all_departments');
        Route::get('/get_all_engine_types', 'EngineTypeController@get_all_engine_types');
        Route::get('/get_all_calls', 'CallController@get_all_calls');
});