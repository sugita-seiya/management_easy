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
use App\User;                           #ユーザーモデルの宣言
use Illuminate\Support\Facades\Auth;    #ユーザークラス(Auth)の宣言
use DB;                                 #DBクラスの宣言

class WorkController extends Controller
{
    public function __construct()
    {
        $this->url               = env('SLACK_WEBHOOK_URL');                           #slackのURL
        $this->channel           = env('SLACK_CHANNEL');                               #slackのチャンネル名
        $this->icon              = env('FACEICON');                                    #アイコン
        $this->year              = date("Y");                                          #当年を取得(yyyy)
        $this->month             = date("m");                                          #当月を取得(m)
        $this->day               = date("j");                                          #当日を取得(d)
        $this->this_month_lastday = date('d', strtotime('last day of this month'));     #当月の最後の日付が出力
    }
    /**
     * Display a listing of the resource.
     *da
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        #ログインユーザーを取得
        $login_user_id = Auth::id();

        #当日を日付をDBから取得
        $work          = new Work;
        $today_date    = $work->Today_Date($this->year,$this->month,$this->day,$login_user_id);
        if (count($today_date) == 0){
            $errer_messege = "システムエラーが発生しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }else{
            $today_date = $today_date[0]->day;
        }

        #当日の最終日チェック(最終日なら次月カレンダー作成)
        if($today_date  == $this->this_month_lastday){
            #次月のカレンダーが情報を取得
            $get_next_month = $work->Get_Next_Month($login_user_id);
            #次月のカレンダーが作成されていなけれが作成
            if(count($get_next_month) == 0){
                $results     = $work->Create_Next_Month($login_user_id);
                if($results == 'false'){
                    $errer_messege = "システムエラーが発生しました。管理者にご連絡ください。";
                    return view('layouts.errer', ['errer_messege' => $errer_messege]);
                }
            }
        }

        #ログインユーザーの勤怠を全て取得
        $user_works    = Work::with('work_section')
                            ->select('*')
                            ->where('user_id', '=', $login_user_id)
                            ->where('year', $this->year)
                            ->where('month', $this->month)
                            ->get();
        if (count($user_works) == 0){
            $errer_messege = "システムエラーが発生しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        #勤怠テーブルの承認フラグを取得
        $approval_flg  = $work->Login_User_Approvelflg_Get($this->year,$this->month,$login_user_id);
        if (count($approval_flg) == 0){
            $errer_messege = "システムエラーが発生しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }else{
            $approval_flg = $approval_flg[0]->approval_flg;
        }

        #ログインユーザーの当日の勤怠ID取得(共通テンプレートで変数を使うため)
        $work_id = $work->Work_Id_Get($this->year,$this->month,$this->day,$login_user_id);
        if ($work_id == null) {
            $errer_messege = "システムエラーが発生しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        #ログインユーザーの権限情報を取得(共通テンプレートで変数を使うため)
        $user                   = new User;
        $authortyid_information = $user->Authortyid_Get($login_user_id);
        if (count($authortyid_information) == 0){
            $errer_messege = "システムエラーが発生しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        return view('works.index', [
            'user_works'            => $user_works,
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
        #備考欄が空の場合は空で更新
        $remark  = request('remark');
        if($remark == null ){
            $remark = '';
        }

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
        $store_work_record->remark          = $remark;
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
        #勤怠のレコード
        $date_work_record = Work::find($work);
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
        $work                  = new Work;
        $worktimes_format_edit = $work->work_time_format($workstart,$workend,$breaktime,$total_worktime);

        #ログインID取得
        $login_user_id         = Auth::id();

        #ログインユーザーの当日の勤怠ID取得(共通テンプレートで変数を使うため)
        $work_id               = $work->Work_Id_Get($this->year,$this->month,$this->day,$login_user_id);
        if ($work_id == null) {
            $errer_messege = "日付取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        #ログインユーザーの権限情報を取得(共通テンプレートで変数を使うため)
        $user                   = new User;
        $authortyid_information = $user->Authortyid_Get($login_user_id);
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
        #ログインユーザーIDを取得
        $login_user_id       = Auth::id();
        $work_new            = new Work;
        $user                = new User;

        #前日以降で出勤していて、退勤されていないレコードを取得
        $null_workend_record = $work_new->Get_Null_Workend($this->year,$this->month,$this->day,$login_user_id);
        #退勤されていないレコードが存在した場合、レコードの更新
        if(count($null_workend_record) >= 1 ){
            #ユーザーテーブルからシステム時間の取得
            $user_information  = $user->UserSystem_Get($login_user_id);
            if (count($user_information) == 0){
                $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
                return view('layouts.errer', ['errer_messege' => $errer_messege]);
            }else{
                #勤怠時間の計算(合計勤務時間 = 終了時間-開始時間-休憩時間)
                $null_fixed_workstart      = $user_information[0]->work_system->fixed_workstart;
                $null_fixed_workend        = $user_information[0]->work_system->fixed_workend;
                $null_fixed_breaktime      = $user_information[0]->work_system->fixed_breaktime;
                $null_total_worktime       = $work_new->Total_WorkTime($null_fixed_workstart,$null_fixed_workend,$null_fixed_breaktime);

                #退勤されていないレコードの更新
                $null_workend_day          = $null_workend_record[0]->day;
                $work_new->Null_Workend_Update($this->year,$this->month,$null_workend_day,$login_user_id,$null_fixed_workend,$null_fixed_breaktime,$null_total_worktime);
            }
        }

        #DBから当日日付の勤怠レコード取得
        $today_work_record = DB::table('works')
                                ->where('user_id', $login_user_id)
                                ->where('year', $this->year)
                                ->where('month', $this->month)
                                ->where('day', $this->day)
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
        $authortyid_information = $user->Authortyid_Get($login_user_id);
        if (count($authortyid_information) == 0){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        #勤怠テーブルの承認フラグを取得
        $approval_flg      = $work_new->Login_User_Approvelflg_Get($this->year,$this->month,$login_user_id);
        if (count($approval_flg) == 0){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }else{
            $approval_flg = $approval_flg[0]->approval_flg;
        }

        #勤怠の承認フラグを取得
        return view('works.edit',
        [
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
        $work            = Work::find($id);
        $login_user_id   = Auth::id();
        $work_new        = new Work;
        if ($request->workstart != null) {                     #出勤時
            $work->workstart = request('workstart');
            $results         = $work->save();
            if ($results != true){
                $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
                return view('layouts.errer', ['errer_messege' => $errer_messege]);
            }

            #勤怠連絡自動送信
            $user            = new User;
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
                        ->Where('id', '=', $login_user_id)
                        ->get();

            #勤怠時間の計算(合計勤務時間 = 終了時間-開始時間-休憩時間)
            $fixed_workstart = $user[0]->work_system->fixed_workstart;
            $fixed_workend   = $user[0]->work_system->fixed_workend;
            $fixed_breaktime = $user[0]->work_system->fixed_breaktime;
            $total_worktime  = $work_new->Total_WorkTime($fixed_workstart,$fixed_workend,$fixed_breaktime);

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

    public function workrequest()
    {
        #DB更新
        $results = DB::table('works')
                    ->where('user_id', request('login_user_id'))
                    ->where('year', $this->year)
                    ->where('month', $this->month)
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
