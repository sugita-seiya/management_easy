<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;    #全ページのビューに適用するための宣言
use DateTime;                           #DataTimeクラスの宣言

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {


        View::composer('*', function($view) {
            $year     = date("Y");                                       #現在の年を取得(yyyy)
            $month    = date("n");                                       #現在の月を取得(m)
            $day      = date("j");                                       #現在の日付を取得(d)
            $datetime = new DateTime("now");
            $day_week = array("日", "月", "火", "水", "木", "金", "土");
            $week     = $day_week[$datetime->format("w")];               #当日の曜日を取得

            $data_information = [
                'year'  => $year,
                'month' => $month,
                'day'   => $day,
                'week'  => $week,
            ];
            $view->with('data_information',$data_information);
        });
        // return $data_information;
    }
}
