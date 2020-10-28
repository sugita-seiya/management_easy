<?php
#---------------------------------------------------------------------------
#連絡機能のコントローラー作成
#index   →連絡一覧
#create  →連絡書き込みページ
#store   →連絡書き込み登録
#show    →
#edit    →
#update  →
#destroy →
#---------------------------------------------------------------------------
namespace App\Http\Controllers;
use App\Contact;               #モデルクラスの宣言
use DateTime;                  #DataTimeクラスの宣言
use Illuminate\Http\Request;


class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $contacts = Contact::all();
        return view('contacts.index',['contacts'=> $contacts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $year     = date("Y");
        $month    = date("m");
        $day      = date("d");
        $week     = array( "日", "月", "火", "水", "木", "金", "土" );
        $datetime = new DateTime("now");
        $week     =$week[$datetime->format("w")];
        return view('contacts.new',['year'=>$year , 'month'=>$month, 'day'=>$day, 'week'=>$week]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contact          = new Contact;
        $contact->year    =request('year');
        $contact->month   =request('month');
        $contact->day     =request('day');
        $contact->subject =request('subject');
        $contact->body    =request('body');
        $contact->user_id = 1;
        $contact->save();
        return redirect()->route('contact.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        //
    }
}
