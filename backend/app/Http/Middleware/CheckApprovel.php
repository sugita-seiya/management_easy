<?php
#------------------------------------------------------------
#管理者のみ閲覧、更新出来るページ設定(1:管理者用 2:一般社員用)
#------------------------------------------------------------
namespace App\Http\Middleware;
use Closure;

use DB;                                 #DBクラスの宣言
use Illuminate\Support\Facades\Auth;    #ユーザークラス(Auth)の宣言

class CheckApprovel
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
        $login_user_id         = Auth::id();
        $loginuser_authrty_id  = DB::table('users')
                                    ->select('authorty_id')
                                    ->where('id', '=', $login_user_id)
                                    ->get();
        $loginuser_authrty_id = $loginuser_authrty_id[0]->authorty_id;
        if($loginuser_authrty_id > 1 ){
            return redirect('/');
        }

        return $next($request);
    }
}
