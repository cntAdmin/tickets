<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => ['auth:web']], function () {
    
    Route::resource('user', 'UserController');
    Route::resource('media', 'AttachmentController')->parameter('media', 'attachment');
    Route::resource('department', 'DepartmentController');
    Route::resource('ticket', 'TicketController');
    Route::resource('ticket.comment', 'CommentController');
    Route::resource('customer', 'CustomerController');
    Route::resource('customer.ticket', 'CustomerTicketController');

    // ? VUE GETTERS
        // ? TICKETS
        Route::get('/get_ticket_counters', 'TicketController@get_ticket_counters');
        Route::get('/get_all_tickets', 'DepartmentController@get_all_departments');
        Route::get('/get_all_ticket_statuses', 'TicketStatusController@get_all_ticket_statuses');
        Route::get('/get_user_role/{user_id?}', function($user_id) {
            $get_user = !$user_id ? auth()->user() : User::find($user_id);

            return response()->json([ 'user_role' => $get_user->getRoleNames()[0] ]);
        });
        
        // ? CREATE
        Route::get('/get_all_users', 'UserController@get_all_users');
        Route::get('/get_all_customers', 'CustomerController@get_all_customers');
        Route::get('/get_all_departments', 'DepartmentController@get_all_departments');
        Route::get('/get_all_calls', 'CallController@get_all_calls');

        // ? DEPARTMENTS
        Route::get('/get_department_users', 'DepartmentController@get_department_users');
        Route::get('/assign_user/{department}/user/{user}', 'DepartmentController@assign_user');
        Route::get('/unassign_user/{department}/user/{user}', 'DepartmentController@unassign_user');
    });


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
