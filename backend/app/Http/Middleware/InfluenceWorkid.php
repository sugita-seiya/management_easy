<?php

namespace App\Http\Middleware;
use Closure;
use App\Contact;                        #連絡事項クラスの宣言
use App\Work;                           #勤怠クラスの宣言
use Illuminate\Support\Facades\Auth;    #ユーザークラスの宣言
use DB;
class InfluenceWorkid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // #DBからシステム日付のレコード取得
        // $login_user_id = Auth::id();
        // $work = Work::where('user_id', $login_user_id)
        //     ->where(function ($query) {
        //         $contact    = new Contact;
        //         $today_date = $contact->date();
        //         $year = $today_date[0];
        //         $query
        //             ->Where('year', '=', $year);
        //     })
        //     ->where(function ($query) {
        //         $contact    = new Contact;
        //         $today_date = $contact->date();
        //         $month = $today_date[1];
        //         $query
        //             ->Where('month', '=', $month);
        //     })
        //     ->where(function ($query) {
        //         $contact    = new Contact;
        //         $today_date = $contact->date();
        //         $day = $today_date[2];
        //         $query
        //             ->Where('day', '=', $day);
        //     })
        //     ->get();


        // #DBからシステム日付のレコード取得
        $login_user_id = Auth::id();
        $contact       = new Contact;
        $today_date    = $contact->date();
        $year          = $today_date[0];
        $month         = $today_date[1];
        $day           = $today_date[2];

        $work = DB::table('works')
                            ->select('*')
                            ->Where('year', '=', $year)
                            ->Where('month', '=', $month)
                            ->Where('day', '=', $day)
                            ->Where('user_id', '=', $login_user_id)
                            ->get();

        $work  = $work[0]->id;
        $request->merge(['work'=>$work]);
        return $next($work);
    }
}
