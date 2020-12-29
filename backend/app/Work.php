<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
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
    public function work_edit($user_id,$year,$month)
    {
        $work = Work::with('work_section')
                    ->select('*')
                    ->where('approval_flg', 2)
                    ->where('user_id', $user_id)
                    ->Where('year', '=', $year)
                    ->Where('month', '=', $month)
                    ->get();
        return $work;
    }

    #----------------------------------------------------------------------------
    #  当月の勤怠を申請and承認したユーザーレコードを取得(Work_approvelController.indexで使用)
    #  処理順 (Work_approvelController->Work.php->User.php->Work_approvelController
    #----------------------------------------------------------------------------
    public function Works_Approvel($year,$month)
    {
        #勤怠を申請and承認したユーザーIDを取得
        $userid_notflg = DB::table('works')
                            ->select('user_id')
                            ->where('approval_flg', 2)
                            ->Where('year', '=', $year)
                            ->Where('month', '=', $month)
                            ->groupBy('user_id')
                            ->get();
        if (count($userid_notflg) == 0) {
            $userid_notflg = "日付取得に失敗しました。管理者にご連絡ください。";
        } else {
            $userid_notflg  = $userid_notflg[0]->user_id;
        }

        #勤怠を申請and承認したユーザーレコードを取得
        $user = new User;
        $in_approval_user = $user->User_All($userid_notflg);
        return $in_approval_user;
    }

    #----------------------------------------------------------------
    #  #勤怠テーブルの承認フラグを取得
    #----------------------------------------------------------------
    public function Login_User_Approvelflg_Get($year,$month,$login_user_id)
    {
        $approval_flg  = DB::table('works')
                            ->select('approval_flg')
                            ->where('user_id', '=', $login_user_id)
                            ->where('year', $year)
                            ->where('month', $month)
                            ->groupBy('approval_flg')
                            ->get();
        return $approval_flg;
    }
    #----------------------------------------------------------------
    #  管理者が承認したらworkテーブルのapproval_flgを承認済に設定
    #----------------------------------------------------------------
    public function Approvel_Update($user_id,$approval_flg,$year,$month)
    {
        $results = DB::table('works')
                            ->where('approval_flg', 2)
                            ->where('user_id', $user_id)
                            ->where('year', $year)
                            ->where('month', $month)
                            ->update([
                                'approval_flg' => $approval_flg
                            ]);
        return $results;
    }

    #----------------------------------------------------------------
    #  ログインユーザーの当日の勤怠ID取得
    #----------------------------------------------------------------
    public function Work_Id_Get($year,$month,$day,$login_user_id)
    {
        // #DBからシステム日付のレコード取得
        $work          = DB::table('works')
                            ->select('*')
                            ->Where('year', '=', $year)
                            ->Where('month', '=', $month)
                            ->Where('day', '=', $day)
                            ->Where('user_id', '=', $login_user_id)
                            ->get();
        $work_id        = $work[0]->id;
        return $work_id;
    }

    #----------------------------------------------------------------
    #  勤怠を押下時にslackへ勤怠連絡をする
    #----------------------------------------------------------------
    public function send_slack($slack_url,$slack_channel,$slack_icon,$login_fname,$login_rname,$slack_boby)
    {
        $url     = $slack_url;
        $message = [
            "channel"    => $slack_channel,                       #チャンネル名
            "username"   => $login_fname.$login_rname,            #投稿者名
            "icon_emoji" => $slack_icon,                          #アイコン
            "text"       => $slack_boby,                          #本文
        ];

        #セッション初期化
        $ch      = curl_init();
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query([
                'payload' => json_encode($message)
            ])
        ];

        #転送用の複数のオプションを設定
        curl_setopt_array($ch, $options);
        #送信実行
        $send_result = curl_exec($ch);
        #セッション終了
        curl_close($ch);
        return $send_result;
    }

    #----------------------------------------------------------------
    #  勤怠時間を任意のフォーマットに変更
    #  HH:MM:SS->HH時MM分
    #  HH:MM:SS->HH時間
    #----------------------------------------------------------------
    public function work_time_format($workstart,$workend,$breaktime,$total_worktime)
    {
        $workstart_format      = date('G時i分',strtotime($workstart));
        $workend_format        = date('G時i分',strtotime($workend));
        $breaktime_format      = date('G時間',strtotime($breaktime));
        $total_worktime_format = date('G時間',strtotime($total_worktime));
        // dd($workstart_format,$workend_format,$breaktime_format,$total_worktime_format);
        $worktimes_format_edit = [
            'workstart'      => $workstart_format,
            'workend'        => $workend_format,
            'breaktime'      => $breaktime_format,
            'total_worktime' => $total_worktime_format,
        ];
        // dd($worktime_format_edit['workstart']);
        return $worktimes_format_edit;
    }
}
