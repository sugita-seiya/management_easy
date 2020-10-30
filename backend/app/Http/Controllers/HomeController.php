<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

#Contactクラスの読み込み(ログイン後、homeページを介さずに連絡一覧ページに遷移)
use App\Contact;

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
        $contacts = Contact::all();
        return view('contacts.index',['contacts'=> $contacts]);
    }
}
