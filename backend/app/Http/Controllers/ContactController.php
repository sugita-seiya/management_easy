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
use App\Section;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $contact    = new Contact;
        $today_date = $contact->date();
        $user       = Auth::user();
        $contacts   = Contact::all();
        $today      = date("Ynj");
        return view('contacts.index',['contacts'=> $contacts, 'user'=>$user,'today'=>$today,'today_date'=>$today_date]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contact    = new Contact;
        $today_date = $contact->date();
        return view('contacts.new',['today_date'=>$today_date]);
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
        $user             = Auth::user();
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
        $user= Auth::user();
        $contact_id = Contact::find($id);
        $contact    = new Contact;
        $today_date = $contact->date();

        if ($user) {
            $login_user_id = $user->id;
        } else {
            $login_user_id = "";
        }
        return view('contacts.show',['contact_id'=>$contact_id,'login_user_id'=>$login_user_id,'today_date'=>$today_date,'user'=>$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contact_id = Contact::find($id);
        $contact    = Contact::all();
        $contact    = new Contact;
        $today_date = $contact->date();
        return view('contacts.edit',['contact_id' => $contact_id, 'contact'=> $contact,'today_date'=>$today_date]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $contact          = Contact::find($id);
        $contact->subject = request('subject');
        $contact->body    = request('body');
        $contact->save();
        return redirect()->route('contact.show',['contact'=>$contact->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact_id = Contact::find($id);
        $contact_id->delete();
        return redirect('contact');
    }
}
