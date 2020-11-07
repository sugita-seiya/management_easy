<?php
#---------------------------------------------------------------------------
#連絡機能のコントローラー作成
#index   →連絡一覧ページ
#create  →連絡書き込みページ
#store   →連絡書き込みページ
#show    →連絡詳細ページ
#edit    →連絡編集ページ
#update  →連絡更新機能
#destroy →連絡削除機能
#---------------------------------------------------------------------------
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Contact;                        #モデルクラスの宣言
use DateTime;                           #DataTimeクラスの宣言
use Illuminate\Support\Facades\Auth;    #ユーザークラスの宣言
// use Illuminate\Support\Facades\DB;




class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $week     = array( "日", "月", "火", "水", "木", "金", "土" );
        $datetime = new DateTime("now");
        $week     =$week[$datetime->format("w")];
    }

    public function index()
    {
        $user     = Auth::user();
        $contacts = Contact::all();
        $today    = date("nj");
        return view('contacts.index',['contacts'=> $contacts, 'user'=>$user,'today'=>$today]);
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
        #入力欄に値がセットされているかバリデーションチェック
        $validatedData = $request->validate([
            'year'    => ['required'],
            'month'   => ['required'],
            'day'     => ['required'],
            'subject' => ['required'],
            'body'    => ['required'],
        ]);
        $user             = \Auth::user();
        $contact          = new Contact;
        $contact->year    =request('year');
        $contact->month   =request('month');
        $contact->day     =request('day');
        $contact->subject =request('subject');
        $contact->body    =request('body');
        $contact->user_id =$user->id;
        $contact->save();
        return redirect()->route('contact.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user= \Auth::user();
        $contact_id = Contact::find($id);

        $week     = array( "日", "月", "火", "水", "木", "金", "土" );
        $datetime = new DateTime("now");
        $week     =$week[$datetime->format("w")];

        if ($user) {
            $login_user_id = $user->id;
        } else {
            $login_user_id = "";
        }
        return view('contacts.show',['contact_id'=>$contact_id,'login_user_id'=>$login_user_id,'week'=>$week,'user'=>$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact,$id)
    {
        $contact_id = Contact::find($id);
        $contact = Contact::all();
        $week     = array( "日", "月", "火", "水", "木", "金", "土" );
        $datetime = new DateTime("now");
        $week     =$week[$datetime->format("w")];
        return view('contacts.edit',['contact_id' => $contact_id, 'contact'=> $contact,'week'=>$week ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact,$id)
    {
        $contact_id = Contact::find($id);
        $contact_id->subject = request('subject');
        $contact_id->body = request('body');
        $contact_id->save();
        return redirect()->route('contact.show',['id'=>$contact_id->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact,$id)
    {
        $contact_id = Contact::find($id);
        $contact_id->delete();
        return redirect('/contacts');
    }
}
