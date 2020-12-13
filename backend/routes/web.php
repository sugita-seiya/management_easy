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
    return redirect('/work');
});

Auth::routes();

#ログインしないと全ページアクセス出来ない
Route::group(['middleware' => ['auth']], function () {
    Route::resource('contact', 'ContactController');
    Route::resource('work', 'WorkController')->only(['index','edit','update']);
    Route::post('work', 'WorkController@workrequest')->name('work.request');

    Route::resource('worksystem', 'WorksystemController')->only(['index','edit','update']);
    Route::get('approvel', 'Work_approvelController@index')->name('user_approvel.index');
    Route::get('approvel/{id}', 'Work_approvelController@wrokindex')->name('work_approvel.index');
    Route::post('approvel/{id}', 'Work_approvelController@update')->name('work_approvel.update');
    // Route::resource('authority/{workindex}', 'AuthorityController@work_index');
});
// Route::get('/home', 'HomeController@index')           ->name('home');
