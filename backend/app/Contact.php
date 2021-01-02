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
}
