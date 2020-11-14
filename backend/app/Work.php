<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    #ユーザー新規登録時に初期データをcreateする時の割り当て許可設定
    protected $fillable = [
        'year',
        'month',
        'day',
        'workstart',
        'workend',
        'breaktime',
        'total_worktime',
        'remark',
        'approval_flg',
        'work_section_id',
        'user_id',
    ];
}
