<?php
#---------------------------------------------------------------------------
#連絡機能用のコントローラー
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
use App\Contact;                        #連絡事項クラスの宣言
use Illuminate\Support\Facades\Auth;    #ログインユーザーの宣言
use App\User;                           #ユーザークラスの宣言
use App\Work;                           #勤怠クラスの宣言

class ContactController extends Controller
{
    public function __construct()
    {
        $this->url     = env('SLACK_WEBHOOK_URL');
        $this->channel = env('SLACK_CHANNEL');
        $this->icon    = env('FACEICON');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        #連絡事項を全て取得
        $contact    = new Contact;
        $today_date = $contact->date();
        $user       = Auth::user();
        $contacts   = Contact::all();
        $today      = date("Ynj");

        #ログインユーザーの権限情報を取得(共通テンプレートで変数を使うため)
        $user                   = new User;
        $authortyid_information = $user->authortyid_get();

        return view('contacts.index',[
            'contacts'              => $contacts,
            'user'                  => $user,
            'today'                 => $today,
            'today_date'            => $today_date,
            'authortyid_information'=> $authortyid_information
        ]);
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

        #ログインユーザーの権限情報を取得(共通テンプレートで変数を使うため)
        $user                   = new User;
        $authortyid_information = $user->authortyid_get();

        return view('contacts.new',[
            'today_date'=>$today_date,
            'authortyid_information'=> $authortyid_information
        ]);
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
        $contact->year    = request('year');
        $contact->month   = request('month');
        $contact->day     = request('day');
        $contact->subject = request('subject');
        $contact->body    = request('body');
        $contact->user_id = $user->id;
        $contact->save();


        #勤怠連絡自動送信
        $work_new        = new Work;
        $user            = new User;
        $login_user_id   = Auth::id();
        $login_user_name = $user->UserName_Get($login_user_id);     #ログインユーザー名取得
        $login_fname     = $login_user_name->f_name;
        $login_rname     = $login_user_name->r_name;
        $slack_body      = request('body');
        $send_result     = $work_new->send_slack($this->url,$this->channel,$this->icon,$login_fname,$login_rname,$slack_body);
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
        $login_user_id  = Auth::id();
        $contact_record = Contact::find($id);
        $contact        = new Contact;
        $today_date     = $contact->date();

        #ログインユーザーの権限情報を取得(共通テンプレートで変数を使うため)
        $user                   = new User;
        $authortyid_information = $user->authortyid_get();

        return view('contacts.show',[
            'contact_record'        => $contact_record,
            'today_date'            => $today_date,
            'login_user_id'         => $login_user_id,
            'authortyid_information'=> $authortyid_information
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $login_user_id  = Auth::id();
        $contact_record = Contact::find($id);
        $contact        = Contact::all();
        $contact        = new Contact;
        $today_date     = $contact->date();


        #ログインユーザーの権限情報を取得(共通テンプレートで変数を使うため)
        $user                   = new User;
        $authortyid_information = $user->authortyid_get();

        return view('contacts.edit',[
            'contact_record'         => $contact_record,
            'contact'                => $contact,
            'today_date'             => $today_date,
            'login_user_id'          => $login_user_id,
            'authortyid_information' => $authortyid_information
        ]);
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
