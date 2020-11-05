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

Auth::routes();
#ログインしないと全ページアクセス出来ない
Route::group(['middleware' => ['auth']], function () {
    Route::get('/contacts', 'ContactController@index')      ->name('contact.index');
    Route::get('/contact/new', 'ContactController@create')  ->name('contact.new');
    Route::post('/contact', 'ContactController@store')      ->name('contact.store');
    Route::get('/contact/{id}', 'ContactController@show')   ->name('contact.show');
    Route::get('/contact/edit/{id}', 'ContactController@edit')   ->name('contact.edit');
    Route::post('/contact/update/{id}', 'ContactController@update')->name('contact.update');
    Route::delete('/contact/{id}', 'ContactController@destroy')->name('contact.destroy');
});




// Route::get('/home', 'HomeController@index')           ->name('home');
