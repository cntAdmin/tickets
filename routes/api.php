<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
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
    Route::resource('file-manager', 'AttachmentController')->parameter('file-manager', 'attachment');
    Route::resource('department', 'DepartmentController');
    Route::resource('ticket', 'TicketController');
    Route::resource('ticket.comment', 'CommentController');
    Route::resource('customer', 'CustomerController');
    Route::resource('customer.ticket', 'CustomerTicketController');
    Route::resource('faqs', 'FaqController');
    Route::resource('post', 'PostController');
    
    // ? GENERICS
    Route::get('/get_all_customers', 'CustomerController@get_all_customers');
    Route::get('/get_all_users', 'UserController@get_all_users');
    Route::get('/get_all_departments', 'DepartmentController@get_all_departments');
    Route::get('/get_all_calls', 'CallController@get_all_calls');
    Route::get('/get_all_tickets', 'DepartmentController@get_all_departments');
    Route::get('/get_all_ticket_statuses', 'TicketStatusController@get_all_ticket_statuses');
    Route::get('/get_all_brands', 'BrandController@get_all_brands');
    Route::get('/get_all_roles', 'RoleController@get_all_roles');
    Route::get('/download/comment/{comment}/file/{attachment}', 'AttachmentController@download');

    // ? VUE GETTERS
        // ? TICKETS
        Route::get('/get_ticket_counters', 'TicketController@get_ticket_counters');
        Route::get('/get_user_role/{user_id?}', function($user_id) {
            $get_user = !$user_id ? auth()->user() : User::find($user_id);

            return response()->json([ 'user_role' => $get_user->getRoleNames()[0] ]);
        });
        Route::get('/ticket/{ticket}/status/{ticketStatus}', 'TicketStatusController@change_status');
        Route::get('/toogle_faqs_ticket/{ticket}', 'TicketController@toogle_faqs_ticket');
        
        // ? BRANDS
        Route::get('/brand/{brand}/model', 'CarModelController@get_brand_models');

        // ? DEPARTMENTS
        Route::get('/get_department_users', 'DepartmentController@get_department_users');
        Route::get('/assign_user/{department}/user/{user}', 'DepartmentController@assign_user');
        Route::get('/unassign_user/{department}/user/{user}', 'DepartmentController@unassign_user');

        // ? CUSTOMERS
        Route::get('/get_customers_count', 'CustomerController@get_customers_count');

        // ? USERS
        Route::get('/get_users_counters', 'UserController@get_users_counters');

        // ? FILE MANAGER
        Route::post('/deleteAllFiles', 'AttachmentController@deleteAll');
        Route::post('/deleteSelectedFiles', 'AttachmentController@deleteSelected');
        Route::get('/get_files_counters', 'AttachmentController@get_files_counters');
        
        // ? BLOGS
        Route::get('/get_posts_counters', 'PostController@get_posts_counters');
        Route::get('/toggle_post/{post}', 'PostController@toggle_post');
        Route::post('/edit_post/{post}', 'PostController@edit_post');

    });

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
