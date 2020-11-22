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
}
