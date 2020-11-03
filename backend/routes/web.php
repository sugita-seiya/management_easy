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

Route::get('/', function () {
    return redirect('/contacts');
});

Route::get('/contacts', 'ContactController@index')     ->name('contact.index')->middleware('auth');
Route::get('/contact/new', 'ContactController@create') ->name('contact.new')->middleware('auth');;
Route::post('/contact', 'ContactController@store')     ->name('contact.store')->middleware('auth');;
Auth::routes();

// Route::get('/home', 'HomeController@index')           ->name('home');
