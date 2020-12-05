<?php

namespace App\Http\Controllers;
use App\Work;
use Illuminate\Http\Request;
use App\Contact;                        #連絡事項クラスの宣言
use App\User;                           #ユーザーモデルの宣言
use Illuminate\Support\Facades\Auth;    #ユーザークラス(Auth)の宣言
use DateTime;                           #DataTimeクラスの宣言
use Carbon\Carbon;                      #日時操作ライブラリの宣言

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contact = new Contact;
        $date = $contact->layout_data();
        $login_user_id = Auth::id();
        $user_works  = Work::with('work_section')
            ->select('*')
            ->where('user_id', '=', $login_user_id)
            ->get();

        // 勤怠レコード取れなかった場合、例外処理
        if ($user_works == null) {
            $errer_messege = "日付取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        return view('works.index', ['user_works' => $user_works,'date' => $date]);
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
        #DBからシステム日付のレコード取得
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

        #システム日付取得チェック
        if (count($work) == 0) {
            $errer_messege = "日付取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        } else {
            $work = $work[0];
        }

        #システム設定時間の取得
        $user = User::with('work_system')
            ->select('*')
            ->get();

        #システム設定時間取得チェック
        if (count($user) == 0) {
            $errer_messege = "日付取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        #システム日付を取得するために連絡事項クラスをインスタンス化
        $contact    = new Contact;
        $today_date = $contact->date();
        return view('works.edit',
        [
            'today_date' => $today_date,
            'work' => $work,
            'user' => $user
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
        if ($request->workstart != null) {
            $work->workstart = request('workstart');
            $work->save();
        } elseif($request->workend != null) {
            #システム設定時間の取得
            $user = User::with('work_system')
                ->select('*')
                ->get();

            #取得した時間をdiffメソッドが使えるフォーマットに変換
            $fixed_work_end   = new DateTime($user[0]->work_system->fixed_workend);
            $fixed_work_start = new DateTime($user[0]->work_system->fixed_workstart);
            $breaktime        = new DateTime($user[0]->work_system->fixed_breaktime);


            #働いた時間の計算(時間 = 終了時間-開始時間-休憩時間)
            $total_time = $fixed_work_end->diff($fixed_work_start);
            $total_time = $total_time->h.':00';
            $total_time = new DateTime($total_time);
            $total_worktime = $total_time->diff($breaktime);
            $total_worktime = $total_worktime->h.':00';

            #DB更新
            $work->total_worktime = $total_worktime;
            $work->workend = request('workend');
            $work->save();
        }else{
            $errer_messege = "登録に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }
        return redirect()->route('work.edit', ['work' => $work->id]);
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
