<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;                                 #DBクラスの宣言
use DateTime;                           #DataTimeクラスの宣言


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
    public function Work_Time_Format($workstart,$workend,$breaktime,$total_worktime)
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

    #----------------------------------------------------------------
    #  当日の日付をDBから取得
    #----------------------------------------------------------------
    public function Today_Date($year,$month,$day,$login_user_id)
    {
        $MonthLastDay = DB::table('works')
                            ->select('day')
                            ->Where('year', '=', $year)
                            ->Where('month', '=', $month)
                            ->Where('day', '=', $day)
                            ->Where('user_id', '=', $login_user_id)
                            ->get();
        return $MonthLastDay;
    }

    #----------------------------------------------------------------
    #  次月カレンダーの作成
    #----------------------------------------------------------------
    public function Create_Next_Month($login_user_id)
    {
        $next_year          = date('Y', strtotime('last day of next month'));   #次月の年を取得
        $next_month         = date('m', strtotime('last day of next month'));   #次月を取得
        $next_month_lastday = date('d', strtotime('last day of next month'));   #次月の最後の日付が出力
        $week               = array( "日", "月", "火", "水", "木", "金", "土" );

        #トランザクション開始
        DB::beginTransaction();
        try{
            for ($i = 1; $i <= $next_month_lastday; $i++){
                $day     = $i;

                #一桁なら二桁にする。(一桁の場合曜日が取得出来ないため)
                if(strlen($day) == 1){
                    $day = '0'.$day;
                }

                $date    = date('w', strtotime($next_year.$next_month.$day));      #システム日付の曜日番号が出力(0〜6)
                $day_week= $week[$date];                                           #日〜土の値が出力される
                if($day_week == "土"){
                    $work_section_id = 3;                                          #法定外休日
                }elseif($day_week == "日") {
                    $work_section_id = 2;                                          #法定休日
                }else{
                    $work_section_id = 1;                                          #出勤
                }
                $null = '';
                Work::create([
                    'year'            => $next_year,
                    'month'           => $next_month,
                    'day'             => $day,
                    'workstart'       => $null,
                    'workend'         => $null,
                    'breaktime'       => $null,
                    'total_worktime'  => $null,
                    'remark'          => $null,
                    'approval_flg'    => '1',
                    'work_section_id' => $work_section_id,
                    'user_id'         => $login_user_id,
                ]);
            }
            DB::commit();
            $create_result = 'true';
            return $create_result;
        }catch (\Exception $e) {
            DB::rollback();
            $create_result = 'false';
            return $create_result;
        }
    }

    #----------------------------------------------------------------
    #  次月のmonthカラムを取得
    #----------------------------------------------------------------
    public function Get_Next_Month($login_user_id)
    {
        $next_year          = date('Y', strtotime('last day of next month'));   #次月の年を取得
        $next_month         = date('m', strtotime('last day of next month'));   #次月を取得

        #次月のmonthカラム情報を取得
        $get_next_month     = DB::table('works')
                                ->select('month')
                                ->Where('year', '=', $next_year)
                                ->Where('month', '=', $next_month)
                                ->Where('user_id', '=', $login_user_id)
                                ->groupBy('month')
                                ->get();
        return $get_next_month;
    }

    #----------------------------------------------------------------
    #  前日以降の出勤していて、退勤がされていないレコードを取得
    #----------------------------------------------------------------
    public function Get_Null_Workend($year,$month,$day,$login_user_id)
    {
        #次月のmonthカラム情報を取得
        $null_workend_record = DB::table('works')
                                ->select('*')
                                ->Where('workstart', '!=', "00:00:00")
                                ->Where('workend', '=', "00:00:00")
                                ->Where('year', '=', $year)
                                ->Where('month', '=', $month)
                                ->Where('day', '<', $day)
                                ->Where('user_id', '=', $login_user_id)
                                ->get();
                                // ->toSql();
                                // dd(count($get_next_month));
        return $null_workend_record;
    }

    #----------------------------------------------------------------
    #  前日以降の出勤していて、退勤がされていないレコードの更新
    #----------------------------------------------------------------
    public function Null_Workend_Update($year,$month,$null_workend_day,$login_user_id,$fixed_workend,$fixed_breaktime,$null_total_worktime)
    {
        #退勤がされていないレコードの更新
        DB::table('works')
            ->where('user_id', $login_user_id)
            ->where('year', $year)
            ->where('month', $month)
            ->where('day', $null_workend_day)
            ->update([
                'workend'        => $fixed_workend,
                'breaktime'      => $fixed_breaktime,
                'total_worktime' => $null_total_worktime
            ]);
            return;
    }
    #----------------------------------------------------------------
    #  勤怠時間の計算(合計勤務時間 = 終了時間-開始時間-休憩時間)
    #----------------------------------------------------------------
    public function Total_WorkTime($fixed_workstart,$fixed_workend,$fixed_breaktime)
    {
            #取得した時間をdiffメソッドが使えるフォーマットに変換
            $fixed_workstart = new DateTime($fixed_workstart);
            $fixed_workend   = new DateTime($fixed_workend);
            $fixed_breaktime = new DateTime($fixed_breaktime);

            #働いた時間の計算(時間 = 終了時間-開始時間-休憩時間)
            $total_time     = $fixed_workend->diff($fixed_workstart);
            $total_time     = $total_time->h.':00';
            $total_time     = new DateTime($total_time);
            $total_worktime = $total_time->diff($fixed_breaktime);
            $total_worktime = $total_worktime->h.':00';

            return $total_worktime;
    }
}
