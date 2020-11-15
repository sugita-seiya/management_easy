<?php

namespace App\Http\Controllers;

use App\Work;
use Illuminate\Http\Request;
use App\Contact;                        #モデルクラスの宣言
use Illuminate\Support\Facades\Auth;    #ユーザークラスの宣言
use DB;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $login_user_id = Auth::id();
        $contact    = new Contact;
        $today_date = $contact->date();
        $year = $today_date[0];
        $month = $today_date[1];
        $day = $today_date[2];
        $work_date = DB::table('works') ->select('year', 'month', 'day')
                                        ->where('user_id', '=', $login_user_id)
                                        ->orWhereYear('year','=', $year)
                                        ->orWhereMonth('month','=', $month)
                                        ->orWhereDay('day','=', $day)
                                        ->get();
        // DB::table('users')->where('votes', '>', 100)->dd();
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
        $contact    = new Contact;
        $today_date = $contact->date();
        
        return view('works.new',['today_date'=>$today_date]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Work $work)
    {
        //
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
