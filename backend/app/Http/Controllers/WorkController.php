<?php

namespace App\Http\Controllers;
use App\Work;
use Illuminate\Http\Request;
use App\Contact;                        #連絡事項クラスの宣言
use App\User;                           #ユーザーモデルの宣言
use Illuminate\Support\Facades\Auth;    #ユーザークラス(Auth)の宣言
use DateTime;                           #DataTimeクラスの宣言
use DB;                                 #DBクラスの宣言

class WorkController extends Controller
{
    public function __construct()
    {
        $this->url     = env('SLACK_WEBHOOK_URL');
        $this->channel = env('SLACK_CHANNEL');
        $this->icon    = env('FACEICON');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        #当月の勤怠一覧を取得
        $contact       = new Contact;
        $today_date    = $contact->date();
        $year          = $today_date[0];
        $month         = $today_date[1];
        $login_user_id = Auth::id();
        $user_works    = Work::with('work_section')
                            ->select('*')
                            ->where('user_id', '=', $login_user_id)
                            ->where('year', $year)
                            ->where('month', $month)
                            ->get();

        #勤怠テーブルの承認フラグを取得
        $approval_flg  = DB::table('works')
                            ->select('approval_flg')
                            ->where('user_id', '=', $login_user_id)
                            ->where('year', $year)
                            ->where('month', $month)
                            ->groupBy('approval_flg')
                            ->get();

        //レコード取得出来なかった場合の例外処理
        if (count($approval_flg) == 0){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }else{
            $approval_flg = $approval_flg[0]->approval_flg;
        }

        //レコード取得出来なかった場合の例外処理
        if (count($user_works) == 0){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        #ログインユーザーの当日の勤怠ID取得(共通テンプレートで変数を使うため)
        $work    = new Work;
        $work_id = $work->work_id_get();

        #ログインユーザーの権限情報を取得(共通テンプレートで変数を使うため)
        $user                  = new User;
        $user_information      = $user->authortyid_get();
        $login_user_authortyid = $user_information[0];
        $admin_user            = $user_information[1];       #管理者用
        $general_user          = $user_information[2];       #一般社員用

        return view('works.index', [
            'user_works'            => $user_works,
            'year'                  => $year,
            'month'                 => $month,
            'login_user_id'         => $login_user_id,
            'approval_flg'          => $approval_flg,
            'work'                  => $work_id,
            'login_user_authortyid' => $login_user_authortyid,
            'admin_user'            => $admin_user,
            'general_user'          => $general_user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function show(Work $work)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function edit(Work $work)
    {
        #DBから当日日付の勤怠レコード取得
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

        #取得チェック
        if (count($work) == 0) {
            $errer_messege = "日付取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        } else {
            $work = $work[0];
        }

        #ログインユーザーのシステム設定時間の取得
        $user_record = User::with('work_system')
                        ->select('*')
                        ->where('id', $login_user_id)
                        ->get();

        #取得チェック
        if (count($user_record) == 0) {
            $errer_messege = "日付取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }
        #システム日付を取得するために連絡事項クラスをインスタンス化
        $contact    = new Contact;
        $today_date = $contact->date();

        #ログインユーザーの権限情報を取得(共通テンプレートで変数を使うため)
        $user                  = new User;
        $user_information      = $user->authortyid_get();
        $login_user_authortyid = $user_information[0];
        $admin_user            = $user_information[1];       #管理者用
        $general_user          = $user_information[2];       #一般社員用

        return view('works.edit',
        [
            'today_date'            => $today_date,
            'work'                  => $work,
            'user_record'           => $user_record,
            'login_user_authortyid' => $login_user_authortyid,
            'admin_user'            => $admin_user,
            'general_user'          => $general_user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $work = Work::find($id);
        if ($request->workstart != null) {                     #出勤時
            $work->workstart = request('workstart');
            $work->save();


            #勤怠連絡自動送信
            $work_new        = new Work;
            $user            = new User;
            $login_user_id   = Auth::id();
            $login_user_name = $user->UserName_Get($login_user_id);     #ログインユーザー名取得
            $login_fname     = $login_user_name->f_name;
            $login_rname     = $login_user_name->r_name;
            $send_result     = $work_new->send_slack($this->url,$this->channel,$this->icon,$login_fname,$login_rname);
            #送信結果取得
            if($send_result != 'ok'){
                $errer_messege = "日付取得に失敗しました。管理者にご連絡ください。";
                return view('layouts.errer', ['errer_messege' => $errer_messege]);
            }
        } elseif($request->workend != null) {                    #退勤時
            #システム設定時間の取得
            $user = User::with('work_system')
                ->select('*')
                ->get();

            #取得した時間をdiffメソッドが使えるフォーマットに変換
            $fixed_work_end   = new DateTime($user[0]->work_system->fixed_workend);
            $fixed_work_start = new DateTime($user[0]->work_system->fixed_workstart);
            $breaktime        = new DateTime($user[0]->work_system->fixed_breaktime);


            #働いた時間の計算(時間 = 終了時間-開始時間-休憩時間)
            $total_time     = $fixed_work_end->diff($fixed_work_start);
            $total_time     = $total_time->h.':00';
            $total_time     = new DateTime($total_time);
            $total_worktime = $total_time->diff($breaktime);
            $total_worktime = $total_worktime->h.':00';

            #DB更新
            $work->total_worktime = $total_worktime;
            $work->workend        = request('workend');
            $work->save();

        }else{
            $errer_messege = "登録に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }
        return redirect()->route('work.edit', ['work' => $work->id]);
    }

    public function workrequest(Request $request)
    {
        $contact    = new Contact;
        $today_date = $contact->date();
        $year = $today_date[0];
        $month = $today_date[1];

        #DB更新
        $execute_result = DB::table('works')
                                ->where('user_id', request('login_user_id'))
                                ->where('year', $year)
                                ->where('month', $month)
                                ->update([
                                    'approval_flg' => request('approval_flg')
                                ]);
        return redirect()->route('work.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function destroy(Work $work)
    {
        //
    }
}
