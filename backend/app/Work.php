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
    #  当月の勤怠を申請and承認したユーザーレコードを取得(Work_approvelController.indexで使用)
    #  処理順 (Work_approvelController->Work.php->User.php->Work_approvelController
    #----------------------------------------------------------------------------
    public function works_approvel()
    {
        $contact    = new Contact;
        $today_date = $contact->date();
        $year       = $today_date[0];
        $month      = $today_date[1];

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

    #----------------------------------------------------------------
    #  ログインユーザーの当日の勤怠ID取得
    #----------------------------------------------------------------
    public function work_id_get()
    {
        // #DBからシステム日付のレコード取得
        $login_user_id = Auth::id();
        $contact       = new Contact;
        $today_date    = $contact->date();
        $year          = $today_date[0];
        $month         = $today_date[1];
        $day           = $today_date[2];

        $work = DB::table('works')
                    ->select('*')
                    ->Where('year', '=', $year)
                    ->Where('month', '=', $month)
                    ->Where('day', '=', $day)
                    ->Where('user_id', '=', $login_user_id)
                    ->get();

        $work_id  = $work[0]->id;
        return $work_id;
    }

    #----------------------------------------------------------------
    #  勤怠を押下時にslackへ勤怠連絡をする
    #----------------------------------------------------------------
    public function send_slack()
    {
        // curl -X POST --data-urlencode "payload={\"channel\": \"test\",\"username\": \"seiya\",\"icon_emoji\": \":snail:\",\"text\": \"こんにちは\"}" https://hooks.slack.com/services/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;
        $url = "https://hooks.slack.com/services/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";
        $message = [
            "channel"    => "#test",
            "username"   => "出勤連絡",
            "icon_emoji" => ":snail:",
            "text"       => "出勤しました。",
        ];

        #セッション初期化
        $ch = curl_init();
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
}
