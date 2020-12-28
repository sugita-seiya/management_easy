<?php

namespace App;

use Illuminate\Database\Eloquent\Model; #モデルクラスの宣言
use Carbon\Carbon;                      #日時操作ライブラリの宣言
use DateTime;                           #DataTimeクラスの宣言

class Contact extends Model
{
    // リレーションの設定。投稿は一つの投稿者に従属する。
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    #created_atを任意のフォーマットで取得(投稿時間出力用)
    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('G時i分');
    }

    #updated_atを任意のフォーマットで取得(投稿日付出力用)
    public function getUpdatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('m月d日');
    }

    #本日システム日付の取得(YYMMDD(曜日))
    public function date()
    {
        $year     = date("Y");              #現在の年を出力する
        $month    = date("n");              #現在の月を出力する
        $day      = date("d");              #現在の日付を出力する

        $datetime = new DateTime("now");
        $day_week = array("日", "月", "火", "水", "木", "金", "土");
        $week     = $day_week[$datetime->format("w")];

        
        $time     = date("H:i");
        $array    = [$year, $month, $day, $week, $time];
        return $array;
    }

    #共通テンプレートに渡すworkデータの変数
    public function layout_data()
    {
        $year     = date("Y");              #現在の年を出力する
        $month    = date("m");              #現在の月を出力する
        $day      = date("d");              #現在の日付を出力する
        $datetime = new DateTime("now");
        $day_week = array("日", "月", "火", "水", "木", "金", "土");
        $week     = $day_week[$datetime->format("w")];
        $time     = date("H:i");
        $array    = [$year, $month, $day, $week, $time];
        return $array;
    }
}
