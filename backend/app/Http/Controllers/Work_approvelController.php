<?php
#-------------------------------------------------------------------------------
#管理者用のコントローラー
#index      -> 当月の勤怠を申請and承認したユーザー名の一覧表示画面
#workindex  -> 勤怠申請したの勤怠一覧を表示画面
#update     -> 管理者が勤怠を承認出来る機能(1:未承認、2:申請中、3:承認済、4:差し戻し)
#-------------------------------------------------------------------------------
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Work;                   #勤怠クラスの宣言
use App\User;                   #ユーザークラスの宣言

class Work_approvelController extends Controller
{
    public function __construct()
    {
        $this->year    = date("Y");      #当年を取得(yyyy)
        $this->month   = date("m");      #当月を取得(m)
        $this->work    = new work;       #勤怠クラスのインスタンス
        $this->user    = new User;       #ユーザークラスのインスタンス
    }

    public function index()
    {
        #勤怠を申請and承認したユーザーレコードを取得
        $approving_user = $this->work->Works_Approvel($this->year,$this->month);
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
        $work_list = $this->work->Work_Edit($user_id,$this->year,$this->month);
        if (count($work_list) == 0) {
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        //勤怠申請したユーザーレコードを取得する
        $user_list = $this->user->User_All($user_id);
        if (count($user_list) == 0) {
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        } else {
            $user_list = $user_list[0];
        }

        return view('work_approvel.workindex',[
            'work_list' => $work_list,
            'user_list' => $user_list,
            'work'      => $this->work
        ]);
    }

    public function update($user_id)
    {
        //管理者が承認or差し戻し時にworkテーブルのapprovel_flgを更新する
        $approval_flg   = request('approval_flg');
        $results        = $this->work->Approvel_Update($user_id,$approval_flg,$this->year,$this->month);
        if ($results != true){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        return redirect()->route('user_approvel.index');
    }
}
