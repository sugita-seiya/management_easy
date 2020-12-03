<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Work_system extends Model
{

    // リレーションの設定。システムは複数のユーザーに従属する。
    public function user()
    {
        return $this->hasMany('App\User');
    }

    #ユーザー新規登録時に初期データをcreateする時の割り当て許可設定
    protected $fillable = [
        'fixed_workstart',
        'fixed_workend',
        'fixed_breaktime',
    ];

    public function work_time_format($workstart,$workend,$breaktime)
    {
        $workstart = date('G時i分',strtotime($workstart));
        $workend   = date('G時i分',strtotime($workend));
        $breaktime = date('G時間',strtotime($breaktime));
        $worktime_array = [$workstart,$workend,$breaktime];
        return $worktime_array;
    }
}
