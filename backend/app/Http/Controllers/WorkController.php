<?php
#---------------------------------------------------------------------------
#勤怠コントローラー
#index       →勤怠一覧一覧ページ
#store       →勤怠レコードの更新機能
#show        →#勤怠レコード詳細ページ
#edit        →当日の勤怠出退勤ページ
#update      →当日勤怠の更新+slack送信機能
#workrequest →当月の勤怠を管理者に送信機能
#---------------------------------------------------------------------------
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

        #ログインユーザーの勤怠を全て取得
        $user_works    = Work::with('work_section')
                            ->select('*')
                            ->where('user_id', '=', $login_user_id)
                            ->where('year', $year)
                            ->where('month', $month)
                            ->get();
        if (count($user_works) == 0){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        #勤怠テーブルの承認フラグを取得
        $work          = new Work;
        $approval_flg  = $work->Login_User_Approvelflg_Get();
        if (count($approval_flg) == 0){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }else{
            $approval_flg = $approval_flg[0]->approval_flg;
        }

        #ログインユーザーの当日の勤怠ID取得(共通テンプレートで変数を使うため)
        $work    = new Work;
        $work_id = $work->Work_Id_Get();
        if ($work_id == null) {
            $errer_messege = "日付取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        #ログインユーザーの権限情報を取得(共通テンプレートで変数を使うため)
        $user                   = new User;
        $authortyid_information = $user->Authortyid_Get();
        if (count($authortyid_information) == 0){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        return view('works.index', [
            'user_works'            => $user_works,
            'year'                  => $year,
            'month'                 => $month,
            'login_user_id'         => $login_user_id,
            'approval_flg'          => $approval_flg,
            'work'                  => $work_id,
            'authortyid_information'=> $authortyid_information
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
    public function store($id)
    {
        #勤怠レコードの更新
        $store_work_record                  = Work::find($id);
        if ($store_work_record == null){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }
        $store_work_record->workstart       = request('workstart');
        $store_work_record->workend         = request('workend');
        $store_work_record->total_worktime  = request('total_worktime');
        $store_work_record->work_section_id = request('work_section_id');
        $store_work_record->remark          = request('remark');
        $results                            = $store_work_record->save();
        if ($results != true){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        return redirect()->route('work.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function show(Work $work)
    {
        #勤怠テーブルのID
        $work_record_id   = $work->id;
        if ($work_record_id == null){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        #勤怠レコードの取得
        $date_work_record = Work::with('work_section')
                                ->select('*')
                                ->where('id', '=', $work_record_id)
                                ->get();
        if (count($date_work_record) == 0){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }else{
            $date_work_record = $date_work_record[0];
        }

        #勤怠時間を任意のフォーマットに変更
        $workstart             = $date_work_record->workstart;
        $workend               = $date_work_record->workend;
        $breaktime             = $date_work_record->breaktime;
        $total_worktime        = $date_work_record->total_worktime;
        $work_new              = new Work;
        $worktimes_format_edit = $work_new->work_time_format($workstart,$workend,$breaktime,$total_worktime);

        ##ログインID取得
        $login_user_id         = Auth::id();

        #ログインユーザーの当日の勤怠ID取得(共通テンプレートで変数を使うため)
        $work    = new Work;
        $work_id = $work->Work_Id_Get();
        if ($work_id == null) {
            $errer_messege = "日付取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        #ログインユーザーの権限情報を取得(共通テンプレートで変数を使うため)
        $user                   = new User;
        $authortyid_information = $user->Authortyid_Get();
        if (count($authortyid_information) == 0){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        return view('works.show',[
            'date_work_record'      => $date_work_record,
            'worktimes_format_edit' => $worktimes_format_edit,
            'login_user_id'         => $login_user_id,
            'work'                  => $work_id,
            'authortyid_information'=> $authortyid_information
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function edit(Work $work)
    {
        #システム日付を取得するために連絡事項クラスをインスタンス化
        $contact       = new Contact;
        $today_date    = $contact->date();
        $year          = $today_date[0];
        $month         = $today_date[1];
        $day           = $today_date[2];
        $login_user_id = Auth::id();

        #DBから当日日付の勤怠レコード取得
        $today_work_record = DB::table('works')
                                ->where('user_id', $login_user_id)
                                ->where('year', $year)
                                ->where('month', $month)
                                ->where('day', $day)
                                ->get();
        if (count($today_work_record) == 0) {
            $errer_messege = "日付取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        } else {
            $today_work_record = $today_work_record[0];
        }

        #ログインユーザーのシステム設定時間の取得
        $user_record = User::with('work_system')
                        ->select('*')
                        ->where('id', $login_user_id)
                        ->get();
        if (count($user_record) == 0) {
            $errer_messege = "日付取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        #ログインユーザーの権限情報を取得(共通テンプレートで変数を使うため)
        $user                   = new User;
        $authortyid_information = $user->Authortyid_Get();
        if (count($authortyid_information) == 0){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        #勤怠テーブルの承認フラグを取得
        $work_new          = new Work;
        $approval_flg      = $work_new->Login_User_Approvelflg_Get();
        if (count($approval_flg) == 0){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }else{
            $approval_flg = $approval_flg[0]->approval_flg;
        }

        #勤怠の承認フラグを取得
        return view('works.edit',
        [
            'today_date'            => $today_date,
            'work_record'           => $work,
            'work'                  => $work->id,
            'login_user_id'         => $login_user_id,
            'user_record'           => $user_record,
            'authortyid_information'=> $authortyid_information,
            'approval_flg'          => $approval_flg
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
            $results         = $work->save();
            if ($results != true){
                $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
                return view('layouts.errer', ['errer_messege' => $errer_messege]);
            }

            #勤怠連絡自動送信
            $work_new        = new Work;
            $user            = new User;
            $login_user_id   = Auth::id();
            $login_user_name = $user->UserName_Get($login_user_id);     #ログインユーザー名取得
            $login_fname     = $login_user_name->f_name;
            $login_rname     = $login_user_name->r_name;
            $slack_boby      = "出勤しました。";
            $send_result     = $work_new->send_slack($this->url,$this->channel,$this->icon,$login_fname,$login_rname,$slack_boby);
            #送信結果取得
            if($send_result != 'ok'){
                $errer_messege = "slack自動送信に失敗しました。管理者にご連絡ください。";
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
            $work->breaktime      = $user[0]->work_system->fixed_breaktime;
            $work->workend        = request('workend');
            $work->total_worktime = $total_worktime;
            $results              =  $work->save();
            if ($results != true){
                $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
                return view('layouts.errer', ['errer_messege' => $errer_messege]);
            }

        }else{
            $errer_messege = "登録に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }
        return redirect()->route('work.edit', ['work' => $work->id]);
    }

    public function workrequest(Request $request)
    {
        #日付取得
        $contact        = new Contact;
        $today_date     = $contact->date();
        $year           = $today_date[0];
        $month          = $today_date[1];

        #DB更新
        $results        = DB::table('works')
                            ->where('user_id', request('login_user_id'))
                            ->where('year', $year)
                            ->where('month', $month)
                            ->update([
                                'approval_flg' => request('approval_flg')
                            ]);
        if ($results == 0){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

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
