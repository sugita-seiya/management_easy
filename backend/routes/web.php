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
    return redirect('/contact');
});

Auth::routes();

#ログインしないと全ページアクセス出来ない
Route::group(['middleware' => ['auth']], function () {
    Route::resource('contact', 'ContactController');
    Route::resource('work', 'WorkController')->only(['index','edit','update']);
});
// Route::get('/home', 'HomeController@index')           ->name('home');
