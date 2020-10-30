<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Contact;                           #Contactクラスの読み込み(ログイン後、homeページを介さずに連絡一覧ページに遷移)

use Illuminate\Support\Facades\Auth;       #ユーザークラスを読み込み

use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // return view('home');
        $user     = Auth::user();
        $contacts = Contact::all();
        return view('contacts.index',['contacts'=> $contacts , 'user' => $user]);
    }
}
