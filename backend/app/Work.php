<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;    #ユーザークラス(Auth)の宣言


class Work extends Model
{
    #----------------------------------------------------------------
    #  リレーションの設定
    #----------------------------------------------------------------
    //ユーザーは一つの勤怠を保持する。
    public function user()
    {
        return $this->hasMany('App\User');
    }
    // 勤怠は一つの出勤区分を保持する。
    public function work_section()
    {
        return $this->belongsTo('App\Work_section');
    }
    #----------------------------------------------------------------
    #  ユーザー新規登録時DB登録時の割当許可設定
    #----------------------------------------------------------------
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

    public function work_edit()
    {
        $login_user_id = Auth::id();
        $work = Work::where('user_id', $login_user_id)
        ->where(function ($query) {
            $contact    = new Contact;
            $today_date = $contact->date();
            $year = $today_date[0];
            $query
                ->Where('year', '=', $year);
        })
        ->where(function ($query) {
            $contact    = new Contact;
            $today_date = $contact->date();
            $month = $today_date[1];
            $query
                ->Where('month', '=', $month);
        })
        ->where(function ($query) {
            $contact    = new Contact;
            $today_date = $contact->date();
            $day = $today_date[2];
            $query
                ->Where('day', '=', $day);
        })
        ->get();

        return $work;
    }

}
