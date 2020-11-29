<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
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

    #----------------------------------------------------------------
    #  ユーザー新規登録時DB登録時の割当許可設定
    #----------------------------------------------------------------
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'f_name','r_name', 'email', 'password','work_system_id',
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

    # ----------------------------------------------------------------
    #  ユーザーに紐づく勤怠を全て取得
    #----------------------------------------------------------------
    // public function getworks()
    // {
    //     return $this->hasMany('App\Contact');
    // }
}
