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
    return view('auth.login');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::resource('user', 'UserController');
    Route::resource('attachment', 'AttachmentController');
    Route::resource('department', 'DepartmentController');
    Route::resource('engineType', 'EngineTypeController');
    Route::resource('ticket', 'TicketController');
    Route::resource('ticket.comments', 'CommentController');
    Route::resource('customer', 'CustomerController');
});

Route::get('/home', 'HomeController@index')->name('home');