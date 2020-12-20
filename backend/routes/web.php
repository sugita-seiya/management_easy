<?php

Route::get('/', function () {
    return redirect('/work');
});

Auth::routes();

// Route::post('login/guest', 'Auth\LoginController@guestLogin')->name('login.guest');

#ログインしないと全ページアクセス出来ない
Route::group(['middleware' => ['auth']], function () {
    #一般社員ページ
    Route::resource('contact', 'ContactController');
    Route::resource('work', 'WorkController')->only(['index','edit','update']);
    Route::post('work', 'WorkController@workrequest')->name('work.request');
    Route::resource('worksystem', 'WorksystemController')->only(['index','edit','update']);
    #管理者専用ページ
    Route::get('approvel', 'Work_approvelController@index')->name('user_approvel.index')->middleware('check_approvel');
    Route::get('approvel/{id}', 'Work_approvelController@wrokindex')->name('work_approvel.index')->middleware('check_approvel');
    Route::post('approvel/{id}', 'Work_approvelController@update')->name('work_approvel.update')->middleware('check_approvel');
});
