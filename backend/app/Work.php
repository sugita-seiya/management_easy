<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;    #ユーザークラス(Auth)の宣言
use DB;                                 #DBクラスの宣言


class Work extends Model
{
    #----------------------------------------------------------------
    #  リレーションの設定
    #----------------------------------------------------------------
    //勤怠は複数のユーザーが保持する。
    public function users()
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

    #----------------------------------------------------------------
    #  勤怠申請したユーザーidを受け取り、当月の勤怠一覧を取得
    #----------------------------------------------------------------
    public function work_edit($user_id)
    {
        $contact    = new Contact;
        $today_date = $contact->date();
        $year       = $today_date[0];
        $month      = $today_date[1];

        $work       = Work::with('work_section')
                        ->select('*')
                        ->where('approval_flg', 2)
                        ->where('user_id', $user_id)
                        ->Where('year', '=', $year)
                        ->Where('month', '=', $month)
                        ->get();

        return $work;
    }

    #----------------------------------------------------------------------------
    #  勤怠を申請したユーザーレコードを取得(Work_approvelController.indexで使用)
    #  処理順 (Work_approvelController->Work.php->User.php->Work_approvelController
    #----------------------------------------------------------------------------
    public function works_approvel()
    {
        #workテーブルから承認中ユーザーを取得
        $userid_notflg = DB::table('works')
                            ->select('user_id')
                            ->where('approval_flg', 2)
                            ->groupBy('user_id')
                            ->get();

        if (count($userid_notflg) == 0) {
            $userid_notflg = "日付取得に失敗しました。管理者にご連絡ください。";
        } else {
            // $work = $work[0];
            $userid_notflg  = $userid_notflg[0]->user_id;
        }

        $user = new User;
        $in_approval_user = $user->user_all($userid_notflg);
        return $in_approval_user;
    }

    #----------------------------------------------------------------
    #  管理者が承認したらworkテーブルのapproval_flgを承認済に設定
    #----------------------------------------------------------------
    public function approvel_update($user_id,$approval_flg)
    {
        $contact    = new Contact;
        $today_date = $contact->date();
        $year = $today_date[0];
        $month = $today_date[1];

        $execute_result = DB::table('works')
                            ->where('approval_flg', 2)
                            ->where('user_id', $user_id)
                            ->where('year', $year)
                            ->where('month', $month)
                            ->update([
                                'approval_flg' => $approval_flg
                            ]);
        return $execute_result;
    }
}
