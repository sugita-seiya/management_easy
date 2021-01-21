<?php
#----------------------------------------------------------------------------------
# ContactControllerテスト
# 実行コマンド: ./vendor/bin/phpunit tests/Feature/Contact/ContactControllerTest.php
#----------------------------------------------------------------------------------

namespace Tests\Feature\Contact;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Contact;                        #連絡事項クラスの宣言
use App\User;                           #ユーザークラスの宣言
use App\Work;                           #勤怠クラスの宣言
use DB;                                 #DBクラスの宣言

class ContactControllerTest extends TestCase
{
    // use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testindex()
    {
        #1-1.データ取得確認(データ有)
        $user = factory(User::class)->create();                # ログインユーザー作成

        #1-2.ログインユーザー情報取得
        $contacts   = Contact::all();                          # 10件取得
        $this->assertEquals(10, count($contacts));

        #1-3.ページ遷移確認(連絡事項一覧)
        $response = $this->actingAs($user)->get('/contact');   # ログイン状態で任意ページへアクセスチェック
        $this->assertEquals(200, $response->status());
    }

    public function testindex_notdata()
    {
        #1-4.データ取得確認(データ無)
        DB::table('contacts')->delete();
        $contacts   = Contact::all();                       # 0件
        if (count($contacts) == 0){
            $contacts = 'null';
        }
        $this->assertEquals('null', $contacts);             #$cocntactsにnullが取得出来る事
    }

    public function testcreate()
    {
        #2-1.ログインユーザー情報取得
        $user                   = factory(User::class)->create();                  # ログインユーザー作成
        $login_user_id          = $user->id;
        $user_new               = new User;
        $authortyid_information = $user_new->Authortyid_Get($login_user_id);
        $this->assertEquals(2, $authortyid_information['login_user_authortyid']);  # ログインユーザーの権限フラグ=2
        $this->assertEquals(1, $authortyid_information['admin_user']);             # 管理者用フラグ=1
        $this->assertEquals(2, $authortyid_information['general_user']);           # 一般者用フラグ=2

        #2-2.ページ遷移確認(連絡事項登録)
        $response = $this->actingAs($user)->get('/contact/create');                # ログイン状態でアクセスチェック
        $this->assertEquals(200, $response->status());
    }

    public function teststore()
    {
        #3-1.連絡事項のテーブル登録確認
        $user             = factory(User::class)->create();           # ログインユーザー作成
        $contact          = new Contact;
        $contact->year    = 2021;
        $contact->month   = 1;
        $contact->day     = 25;
        $contact->subject = 'TEST';
        $contact->body    = 'TEST';
        $contact->user_id = $user->id;
        $results          = $contact->save();
        $this->assertEquals(true, $results);                          # DB登録結果がtrue

        #3-2.ログインユーザー名取得確認
        $user_new         = new User;
        $login_user_id    = $user->id;
        $login_user_name  = $user_new->UserName_Get($login_user_id);  #ログインユーザー名取得
        $login_fname      = $login_user_name->f_name;
        $login_rname      = $login_user_name->r_name;
        $this->assertEquals('sugita', $login_fname);                  # 姓 = sugita
        $this->assertEquals('seiya', $login_rname);                   # 名 = seiya

        #3-3.slack通知確認
        $work_new         = new Work;
        $url              = config('app.SLACK_WEBHOOK_URL');          #slackのURL
        $channel          = config('app.SLACK_CHANNEL');              #slackのチャンネル名
        $icon             = config('app.FACEICON');                   #アイコン
        $send_result      = $work_new->send_slack($url,$channel,$icon,$login_fname,$login_rname,$contact->body);
        $this->assertEquals('ok',$send_result);                       # 送信結果=ok

        #3-4.ページ遷移確認(連絡事項一覧)
        $response         = $this->actingAs($user)->get('/contact');  # ログイン状態で任意ページへアクセスチェック
        $this->assertEquals(200, $response->status());
    }

    public function testshow()
    {
        #4-1.連絡事項編集ページのID取得確認
        $user             = factory(User::class)->create();           # ログインユーザー作成
        $login_user_id    = $user->id;
        $contact          = new Contact;
        $contact->id      = 1;
        $contact->year    = 2021;
        $contact->month   = 1;
        $contact->day     = 25;
        $contact->subject = 'TEST';
        $contact->body    = 'TEST';
        $contact->user_id = $login_user_id;
        $contact->save();
        $contact_id       = DB::table('contacts')
                            ->select('id')
                            ->where('id', 1)
                            ->get();
        $this->assertEquals(1,$contact_id[0]->id);                       # ID = 1



        #4-2.ログインユーザー情報取得
        $user_new               = new User;
        $authortyid_information = $user_new->Authortyid_Get($login_user_id);
        $this->assertEquals(2, $authortyid_information['login_user_authortyid']);  # ログインユーザーの権限フラグ=2
        $this->assertEquals(1, $authortyid_information['admin_user']);             # 管理者用フラグ=1
        $this->assertEquals(2, $authortyid_information['general_user']);           # 一般者用フラグ=2

        #4-3.ページ遷移確認(連絡事項詳細)
        $response         = $this->actingAs($user)->get('/contact/$contact_id[0]->id');  # ログイン状態で任意ページへアクセスチェック
        $this->assertEquals(200, $response->status());
    }

    public function testedit()
    {
        #5-1.連絡事項編集ページのID取得確認
        $user             = factory(User::class)->create();           # ログインユーザー作成
        $login_user_id    = $user->id;
        $contact          = new Contact;
        $contact->id      = 2;
        $contact->year    = 2021;
        $contact->month   = 1;
        $contact->day     = 25;
        $contact->subject = 'TEST';
        $contact->body    = 'TEST';
        $contact->user_id = $login_user_id;
        $contact->save();
        $contact_id       = DB::table('contacts')
                            ->select('id')
                            ->where('id', 2)
                            ->get();
        $this->assertEquals(2,$contact_id[0]->id);                       # ID = 2

        #5-2.ログインユーザー情報取得
        $user_new               = new User;
        $authortyid_information = $user_new->Authortyid_Get($login_user_id);
        $this->assertEquals(2, $authortyid_information['login_user_authortyid']);  # ログインユーザーの権限フラグ=2
        $this->assertEquals(1, $authortyid_information['admin_user']);             # 管理者用フラグ=1
        $this->assertEquals(2, $authortyid_information['general_user']);           # 一般者用フラグ=2

        #5-3.ページ遷移確認(連絡事項編集)
        $response         = $this->actingAs($user)->get('/contact/$contact_id[0]->id/edit');  # ログイン状態で任意ページへアクセスチェック
        $this->assertEquals(200, $response->status());
    }

    public function testupdate()
    {
        # 6-1.連絡事項更新レコードID取得確認
        $user             = factory(User::class)->create();           # ログインユーザー作成
        $login_user_id    = $user->id;
        $contact          = new Contact;
        $contact->id      = 3;
        $contact->year    = 2021;
        $contact->month   = 1;
        $contact->day     = 25;
        $contact->subject = 'TEST';
        $contact->body    = 'TEST';
        $contact->user_id = $login_user_id;
        $contact->save();

        $contact_id       = DB::table('contacts')
                            ->select('id')
                            ->where('id', 3)
                            ->get();

        $this->assertEquals(3,$contact_id[0]->id);                           #ID=3

        # 6-2. 連絡事項更新レコード更新確認
        $results = DB::table('contacts')
                    ->where('id', $contact_id[0]->id)
                    ->update([
                        'year'    => 2021,
                        'month'   => 1,
                        'day'     => 30,
                        'subject' => 'TEST1',
                        'body'    => 'TEST1',
                    ]);
        $contact = DB::table('contacts')
                        ->select('*')
                        ->where('id', 3)
                        ->get();
        $this->assertEquals(2021, $contact[0]->year);                    #更新されていることを確認
        $this->assertEquals(1, $contact[0]->month);                      #更新されていることを確認
        $this->assertEquals(30, $contact[0]->day);                       #更新されていることを確認
        $this->assertEquals('TEST1', $contact[0]->subject);                #更新されていることを確認
        $this->assertEquals('TEST1', $contact[0]->body);                   #更新されていることを確認

        # 6-3.ページ遷移確認(連絡事項一覧)
        $response = $this->actingAs($user)->get(route('work.index'));
        $response->assertStatus(200);
    }

    public function testdestroy()
    {
        # 7-1.連絡事項更新レコードID取得確認
        $user             = factory(User::class)->create();           # ログインユーザー作成
        $login_user_id    = $user->id;
        $contact          = new Contact;
        $contact->id      = 4;
        $contact->year    = 2021;
        $contact->month   = 1;
        $contact->day     = 25;
        $contact->subject = 'TEST';
        $contact->body    = 'TEST';
        $contact->user_id = $login_user_id;
        $contact->save();

        $contact_id       = DB::table('contacts')
                            ->select('*')
                            ->where('id', 4)
                            ->first();

        $this->assertEquals(4,$contact_id->id);                    #ID=4

        # 7-2. 連絡事項更新レコード削除確認
        $results    = DB::table('contacts')
                        ->where('id', $contact_id->id)
                        ->delete();

        $this->assertEquals(true, $results);                          # DB登録結果がtrue
    }
}
