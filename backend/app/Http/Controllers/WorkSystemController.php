<?php

namespace App\Http\Controllers;

use App\Work_system;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;    #ユーザークラス(Auth)の宣言
use App\User;                           #ユーザーモデルの宣言
use App\Work;

class WorkSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//         $work = new Work;
//         $work = $work->work_edit();

// $work =  $work[0]->id;

        #ユーザーに紐づいているシステム設定を取得
        $login_user_id = Auth::id();
        $user          = User::with('work_system')
            ->select('*')
            ->where('id', '=', $login_user_id)
            ->get();

        if ($user == null) {
            $errer_messege = "取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        return view('worksystems.index', ['user' => $user]);
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
        $worktimes = $worksystem->work_time_format($workstart,$workend,$breaktime);

        return view('worksystems.edit',[
            'worksystem_id' => $worksystem_id,
            'worktimes'  => $worktimes
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
