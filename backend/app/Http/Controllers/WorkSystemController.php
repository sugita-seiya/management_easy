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
        #ユーザーに紐づいているシステム設定を取得
        // $login_user_id = Auth::id();
        // $user  = User::with('work_system')
        // ->select('*')
        // ->where('id', '=', $login_user_id)
        // ->get();

        // if ($user == null) {
        //     $errer_messege = "取得に失敗しました。管理者にご連絡ください。";
        //     return view('errer', ['errer_messege' => $errer_messege]);
        // }

        // return view('worksystems.index', ['user' => $user]);
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
    public function edit(Work_system $work_system)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Work_system  $work_system
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Work_system $work_system)
    {
        //
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
