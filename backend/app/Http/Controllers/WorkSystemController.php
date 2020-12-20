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
        $login_user_id      = Auth::id();
        $loginuser_record = User::with('work_system')
                                ->select('*')
                                ->where('id', '=', $login_user_id)
                                ->get();

        if ($loginuser_record == null) {
            $errer_messege = "取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        #ログインユーザーの当日の勤怠ID取得(共通テンプレートで勤怠idが使える様にするため)
        $work    = new Work;
        $work_id = $work->work_id_get();

        #ログインユーザーの権限情報を取得(共通テンプレートで変数を使うため)
        $user                  = new User;
        $user_information      = $user->authortyid_get();
        $login_user_authortyid = $user_information[0];
        $admin_user            = $user_information[1];       #管理者用
        $general_user          = $user_information[2];       #一般社員用
        return view('worksystems.index', [
            'loginuser_record'      => $loginuser_record,
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
        $workstart  = $worksystem_id->fixed_workstart;
        $workend    = $worksystem_id->fixed_workend;
        $breaktime  = $worksystem_id->fixed_breaktime;

        #勤怠時間のフォーマット変更(HH:MM:SS->HH時MM分)
        $worksystem = new Work_system;
        $worktimes  = $worksystem->work_time_format($workstart,$workend,$breaktime);

        #ログインユーザーの当日の勤怠ID取得(共通テンプレートで勤怠idが使える様にするため)
        $work    = new Work;
        $work_id = $work->work_id_get();

        #ログインユーザーの権限情報を取得(共通テンプレートで変数を使うため)
        $user                  = new User;
        $user_information      = $user->authortyid_get();
        $login_user_authortyid = $user_information[0];
        $admin_user            = $user_information[1];       #管理者用
        $general_user          = $user_information[2];       #一般社員用
        // dd($login_user_authortyid,$admin_user);

        return view('worksystems.edit',[
            'worksystem_id' => $worksystem_id,
            'worktimes'     => $worktimes,
            'work'                  => $work_id,
            'login_user_authortyid' => $login_user_authortyid,
            'admin_user'            => $admin_user,
            'general_user'          => $general_user
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
        $worksystem = Work_system::find($id);
        $worksystem->fixed_workstart = request('fixed_workstart');
        $worksystem->fixed_workend   = request('fixed_workend');
        $worksystem->fixed_breaktime = request('fixed_breaktime');
        $worksystem->save();
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
