<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;    #ユーザークラス(Auth)の宣言
use DB;                                 #DBクラスの宣言

class User extends Authenticatable
{
    use Notifiable;
    #----------------------------------------------------------------
    #  ユーザー新規登録時DB登録時の割当許可設定
    #----------------------------------------------------------------
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'f_name',
        'r_name',
        'email',
        'password',
        'work_system_id',
        'authorty_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    #----------------------------------------------------------------
    #  リレーションの設定
    #----------------------------------------------------------------
    # ユーザーは複数の投稿を保持する。
    public function contacts()
    {
        return $this->hasMany('App\Contact');
    }

    //ユーザーは一つの勤怠を保持する。
    public function work()
    {
        return $this->belongsTo('App\Work');
    }

    //ユーザーは一つの勤怠設定を保持する。
    public function work_system()
    {
        return $this->belongsTo('App\Work_system');
    }

    //ユーザーは一つの権限を保持する。
    public function authority()
    {
        return $this->belongsTo('App\Authority');
    }

    #----------------------------------------------------------------------------
    #  勤怠を申請したユーザーレコードを取得(Work_approvelController.indexで使用)
    #  処理順 (Work_approvelController->Work.php->User.php->Work_approvelController
    #----------------------------------------------------------------------------
    public function user_all($user_id)
    {
        $user = DB::table('users')
                    ->where('id', $user_id)
                    ->get();
        return $user;
    }

    #----------------------------------------------------------------
    #  ログインしているユーザーの権限情報を取得(管理者:1,一般社員:2)
    #----------------------------------------------------------------
    public function authortyid_get()
    {
        // #userテーブルからログインユーザーの権限情報を取得
        $login_user_id          = Auth::id();
        $dbget_authortyid       = DB::table('users')
                                    ->select('authorty_id')
                                    ->Where('id', '=', $login_user_id)
                                    ->get();

        $login_user_authortyid  = $dbget_authortyid[0]->authorty_id;
        $admin_user             = 1;                              #管理者用
        $general_user           = 2;                              #一般社員用
        $authortyid_information = [
            'login_user_authortyid' => $login_user_authortyid,
            'admin_user'            => $admin_user,
            'general_user'          => $general_user
        ];

        return $authortyid_information;
    }

    #----------------------------------------------------------------------------
    #  ログインユーザーの名前を取得
    #----------------------------------------------------------------------------
    public function UserName_Get($user_id)
    {
        $user_name = DB::table('users')
                    ->select('f_name','r_name')
                    ->where('id', $user_id)
                    ->get();
        #取得チェック
        if (count($user_name) == 0) {
            $errer_messege = "日付取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }else{
            $user_name = $user_name[0];
        }

        return $user_name;
    }
}
