<?php
#-------------------------------------------------------------------------------
#管理者用のコントローラー
#index      -> 当月の勤怠を申請and承認したユーザー名を表示
#workindex  -> 勤怠申請したの勤怠一覧を表示
#update     -> 管理者が勤怠を承認したらworkテーブルのapproval_flgを更新(更新フラグ = 3)
#-------------------------------------------------------------------------------
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Work;                   #勤怠クラスの宣言
use App\User;                   #ユーザークラスの宣言

class Work_approvelController extends Controller
{
    public function __construct()
    {
        $this->year    = date("Y");                #当年を取得(yyyy)
        $this->month   = date("m");                #当月を取得(m)
    }

    public function index()
    {
        #勤怠を申請and承認したユーザーレコードを取得
        $work           = new work;
        $approving_user = $work->Works_Approvel($this->year,$this->month);
        //レコード取得出来なかった場合、nullをセット
        if (count($approving_user) == 0) {
            $approving_user = null;
        }

        return view('work_approvel.index', ['approving_user' => $approving_user]);
    }
    public function userindex()
    {
        #社員全員の名前を取得
        $all_user_name = User::all();
        //レコード取得出来なかった場合、nullをセット
        if (count($all_user_name) == 0) {
            $all_user_name = null;
        }

        return view('work_approvel.userindex', ['all_user_name' => $all_user_name]);
    }

    public function wrokindex($user_id)
    {
        //勤怠申請したユーザーidを受け取り、当月の勤怠一覧を取得する
        $work      = new work;
        $work_list = $work->Work_Edit($user_id,$this->year,$this->month);
        if (count($work_list) == 0) {
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        //勤怠申請したユーザーレコードを取得する
        $user      = new User;
        $user_list = $user->User_All($user_id);
        if (count($user_list) == 0) {
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        } else {
            $user_list = $user_list[0];
        }

        return view('work_approvel.workindex',[
            'work_list' => $work_list,
            'user_list' => $user_list,
            'work'      => $work
        ]);
    }

    public function update($user_id)
    {
        //管理者が承認or差し戻し時にworkテーブルのapprovel_flgを更新する
        $approval_flg   = request('approval_flg');
        $work           = new work;
        $results        = $work->Approvel_Update($user_id,$approval_flg,$this->year,$this->month);
        if ($results != true){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        return redirect()->route('user_approvel.index');
    }
}
