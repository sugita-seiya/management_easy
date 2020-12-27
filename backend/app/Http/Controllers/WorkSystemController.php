<?php

namespace App\Http\Controllers;

use App\Work_system;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;    #ユーザークラス(Auth)の宣言
use App\User;                           #ユーザークラスの宣言
use App\Work;                           #勤怠クラスの宣言

class WorkSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        #ユーザーに紐づいているシステム設定を取得
        $login_user_id    = Auth::id();
        $loginuser_record = User::with('work_system')
                                ->select('*')
                                ->where('id', '=', $login_user_id)
                                ->get();
        if (count($loginuser_record) == 0){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        #ログインユーザーの当日の勤怠ID取得(共通テンプレートで勤怠idが使える様にするため)
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

        return view('worksystems.index', [
            'loginuser_record'      => $loginuser_record,
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Work_system  $work_system
     * @return \Illuminate\Http\Response
     */
    public function show(Work_system $work_system)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Work_system  $work_system
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $worksystem = new Work_system;
        $worksystem_id = Work_system::find($id);
        $workstart     = $worksystem_id->fixed_workstart;
        $workend       = $worksystem_id->fixed_workend;
        $breaktime     = $worksystem_id->fixed_breaktime;

        #勤怠時間のフォーマット変更(HH:MM:SS->HH時MM分)
        $worksystem    = new Work_system;
        $worktimes     = $worksystem->work_time_format($workstart,$workend,$breaktime);

        #ログインユーザーの当日の勤怠ID取得(共通テンプレートで勤怠idが使える様にするため)
        $work          = new Work;
        $work_id       = $work->Work_Id_Get();
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

        return view('worksystems.edit',[
            'worksystem_id'         => $worksystem_id,
            'worktimes'             => $worktimes,
            'work'                  => $work_id,
            'authortyid_information'=> $authortyid_information
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Work_system  $work_system
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $worksystem                  = Work_system::find($id);
        $worksystem->fixed_workstart = request('fixed_workstart');
        $worksystem->fixed_workend   = request('fixed_workend');
        $results                     = $worksystem->save();
        if ($results != true){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        return redirect()->route('worksystem.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Work_system  $work_system
     * @return \Illuminate\Http\Response
     */
    public function destroy(Work_system $work_system)
    {
        //
    }
}
