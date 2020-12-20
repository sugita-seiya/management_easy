<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DateTime;                                  #DataTimeクラスの宣言
use App\Work;                                  #Workクラスの宣言
use Illuminate\Support\Facades\Auth;           #Authクラスの宣言
use App\Work_system;                           #Work_systemクラスの宣言
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'f_name'   => ['required', 'string', 'max:255'],
            'r_name'   => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $year             = date("Y");              #現在の年を出力する
        $month            = date("m");              #現在の月を出力する
        $week             = array( "日", "月", "火", "水", "木", "金", "土" );
        $thisMonthLastDay = date('d', strtotime('last day of this month'));     #当月の最後の日付が出力

        $work_system = Work_system::create([
            'fixed_workstart' => '00:00:00',
            'fixed_workend'   => '00:00:00',
            'fixed_breaktime' => '01:00:00',
        ]);

        $user = User::create([
            'f_name'         => $data['f_name'],
            'r_name'         => $data['r_name'],
            'email'          => $data['email'],
            'work_system_id' => $work_system->id,
            'password'       => Hash::make($data['password']),
            'authorty_id'    => '2',
        ]);

        for ($i = 1; $i <= $thisMonthLastDay; $i++){
            $day     = $i;

            #一桁なら二桁にする。(一桁の場合曜日が取得出来ないため)
            if(strlen($day) == 1){
                $day = '0'.$day;
            }

            $date    = date('w', strtotime($year.$month.$day));      #システム日付の曜日番号が出力(0〜6)
            $day_week= $week[$date];                                 #日〜土の値が出力される
            if($day_week == "土"){
                $work_section_id = 3;                                #法定外休日
            }elseif($day_week == "日") {
                $work_section_id = 2;                                #法定休日
            }else{
                $work_section_id = 1;                                #出勤
            }

            if ($user) {
                $user->id;
            }


            DB::table('works')->insert([
                'year'            => $year,
                'month'           => $month,
                'day'             => $day,
                'workstart'       => '',
                'workend'         => '',
                'breaktime'       => '',
                'total_worktime'  => '',
                'remark'          => 'なし',
                'approval_flg'    => '1',
                'work_section_id' => $work_section_id,
                'user_id'         => $user->id,
            ]);

            // Work::create([
            //     'year'            => $year,
            //     'month'           => $month,
            //     'day'             => $day,
            //     'workstart'       => '00:00:00',
            //     'workend'         => '00:00:00',
            //     'breaktime'       => '00:00:00',
            //     'total_worktime'  => '00:00:00',
            //     'remark'          => 'なし',
            //     'approval_flg'    => '1',
            //     'work_section_id' => $work_section_id,
            //     'user_id'         => $user->id,
            // ]);
        }
        return $user;
    }
}
