<?php
#---------------------------------------------------------------------------
#連絡事項コントローラー
#index   →連絡一覧ページ
#create  →連絡書き込みページ
#store   →連絡書き込みページ+slack送信機能
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
        $this->url     = config('app.SLACK_WEBHOOK_URL'); #slackのURL
        $this->channel = config('app.SLACK_CHANNEL');     #slackのチャンネル名
        $this->icon    = config('app.FACEICON');          #アイコン
        $this->user    = new User;                        #ユーザークラスのインスタンス
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        #連絡事項を全て取得
        $contacts   = Contact::all();
        if (count($contacts) == 0){
            $contacts = 'null';
        }

        #ログインID取得
        $login_user_id         = Auth::id();

        #ログインユーザーの権限情報を取得(共通テンプレートで変数を使うため)
        $authortyid_information = $this->user->Authortyid_Get($login_user_id);
        return view('contacts.index',[
            'contacts'               => $contacts,
            'authortyid_information' => $authortyid_information
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        #ログインID取得
        $login_user_id         = Auth::id();

        #ログインユーザーの権限情報を取得(共通テンプレートで変数を使うため)
        $authortyid_information = $this->user->Authortyid_Get($login_user_id);

        return view('contacts.new',[
            'authortyid_information' => $authortyid_information
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
        $results          = $contact->save();
        if ($results != true){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }


        #勤怠連絡自動送信
        $login_user_id   = Auth::id();
        $login_user_name = $this->user->UserName_Get($login_user_id);     #ログインユーザー名取得
        $login_fname     = $login_user_name->f_name;
        $login_rname     = $login_user_name->r_name;
        $slack_body      = request('body');
        $work_new        = new Work;
        // dd($this->url,$this->channel,$this->icon,$login_fname,$login_rname,$slack_body);
        $send_result     = $work_new->send_slack($this->url,$this->channel,$this->icon,$login_fname,$login_rname,$slack_body);
        // dd($send_result);
        if ($send_result != 'ok'){
            $errer_messege = "エラーが発生しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }
        return redirect()->route('contact.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show($contact)
    {
        $contact_record = Contact::find($contact);
        if ($contact_record == null){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        #ログインID取得
        $login_user_id  = Auth::id();

        #ログインユーザーの権限情報を取得(共通テンプレートで変数を使うため)
        $authortyid_information = $this->user->Authortyid_Get($login_user_id);

        return view('contacts.show',[
            'contact_record'        => $contact_record,
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
    public function edit($contact)
    {
        $contact_record = Contact::find($contact);
        #レコード取得出来なかった場合の例外処理
        if ($contact_record == null){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        #ログインID取得
        $login_user_id          = Auth::id();

        #ログインユーザーの権限情報を取得(共通テンプレートで変数を使うため)
        $authortyid_information = $this->user->Authortyid_Get($login_user_id);

        return view('contacts.edit',[
            'contact_record'         => $contact_record,
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
    public function update($contact)
    {
        $contact          = Contact::find($contact);
        if ($contact == null){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }
        $contact->subject = request('subject');
        $contact->body    = request('body');
        $results          = $contact->save();
        if ($results != true){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        return redirect()->route('contact.show',['contact'=>$contact->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy($contact)
    {
        $contact_id = Contact::find($contact);
        if ($contact_id == null){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }
        $results    = $contact_id->delete();
        if ($results != true){
            $errer_messege = "レコード取得に失敗しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }

        return redirect('contact');
    }
}
