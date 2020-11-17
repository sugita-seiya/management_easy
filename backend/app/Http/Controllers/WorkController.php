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

                                        // $query->where(function ($query) {
                                        //     $query->whereNull('A')
                                        //         ->orWhere('A', '0');
                                        // })
                                        // ->where(function ($query) {
                                        //     $query->whereNull('B')
                                        //         ->orWhere('B', '0');
                                        // })
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
                    ->Where('year','=', $year);
            })
            ->where(function ($query) {
                $contact    = new Contact;
                $today_date = $contact->date();
                $month = $today_date[1];
                $query
                    ->Where('month','=', $month);
            })
            ->where(function ($query) {
                $contact    = new Contact;
                $today_date = $contact->date();
                $day = $today_date[2];
                $query
                    ->Where('day','=', $day);
            })
            ->get();


        if (count($work) == 0){
            $errer_messege = "日付取得に失敗しました。管理者にご連絡ください。";
            return view('errer',['errer_messege'=>$errer_messege]);
        }else{
            $work = $work[0];
        }


        // dd($work);

        #システム日付をインスタンス化
        $contact    = new Contact;
        $today_date = $contact->date();
        return view('works.edit',['today_date'=>$today_date,'work'=>$work]);
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
        if($request->workstart != null){
            $work->workstart = request('workstart');
            $work->save();
        }else{
            $work->workend = request('workend');
            $work->save();
        }
        return redirect()->route('work.edit',['work'=>$work->id]);
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
