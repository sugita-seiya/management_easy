<?php

namespace App\Http\Controllers;

use App\Authority;
use Illuminate\Http\Request;
use App\Work;                     #勤怠クラスの宣言

class AuthorityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //当月に申請した全ユーザー名の表示
        // 1.workテーブルからflg=1に該当するユーザーIDを取得
        // 2.ユーザーIDを使ってユーザーのデータを取得
        $work = new work;
        $approving_user = $work->works_approvel();
        return view('authorities.index', ['approving_user' => $approving_user]);
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
     * @param  \App\Authority  $authority
     * @return \Illuminate\Http\Response
     */
    public function show(Authority $authority,$id)
    {
        #勤怠承認したユーザーの勤怠詳細を表示
        return view('authorities.show');
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  \App\Authority  $authority
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit(Authority $authority)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Authority  $authority
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Authority $authority)
    {
        //
    }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\Authority  $authority
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(Authority $authority)
    // {
    //     //
    // }
}
