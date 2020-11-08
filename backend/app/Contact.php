<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;               #日時操作ライブラリの宣言

class Contact extends Model
{
    // リレーションの設定。投稿は一つの投稿者に従属する。
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    #created_atを任意のフォーマットで取得
    public function getCreatedAtAttribute($date) {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('H時i分');
    }

    #updated_atを任意のフォーマットで取得
    public function getUpdatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('m月d日');
    }




    #DBから日付をcreated_atを取得して当日日付のみ時間を戻り値にする
    // public function getCreatedAtAttribute($date) {
    //     $today_date = Carbon::now()->toDateString();
    //     $db_date    = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');


    //     if($date == $today_date){
    //         return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('H:i');
    //     }else{
    //         return null;
    //     }
    // }
}

