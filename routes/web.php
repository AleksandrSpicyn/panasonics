<?php

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

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/cirquedusoleil/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
Auth::routes(['verify' => true]);
Route::get('/', 'WelcomeController@index')->name('home');
Route::get('/jobs', 'JobController@index');
Route::post('/jobs', 'JobController@view');
Route::get('/ticket/view', 'AuthController@ticket');
Route::post('/auth', 'AuthController@auth');
Route::post('/register', 'AuthController@register');
Route::get('/reset', 'AuthController@resetByTicket')->name('reset');
Route::post('/reset', 'AuthController@reset');
Route::post('/reset_by_email', 'AuthController@resetByEmail');
Route::post('/share', 'ShareController@share');
Route::post('/load-more', 'JobController@loadMore');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::patch('/admin/job', 'JobController@update');
    Route::get('/job/{id}', 'JobController@get');
    Route::get('/cirquedusoleil/admin', 'AdminController@index');
    Route::post('/rotate', 'AdminController@rotate');
    Route::get('/admin/{status}', 'AdminController@index');
    Route::get('/add_roles', 'AdminController@roles');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/job', 'JobController@store');
    Route::patch('/job', 'JobController@update');
    Route::get('/profile', 'ProfileController@index');
    Route::patch('/profile', 'ProfileController@update');
    Route::post('/like', 'LikeController@store');
    Route::post('/logout', 'AuthController@logout');
    Route::post('/switch_password', 'AuthController@switchPassword');
});


Route::get('/home', 'HomeController@index')->name('home');
