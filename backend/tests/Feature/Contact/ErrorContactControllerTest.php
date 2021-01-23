<?php
#------------------------------------------------------------------------------------
# ContactControllerテスト
# 実行コマンド: ./vendor/bin/phpunit tests/Feature/Contact/ErrorContactControllerTest
#------------------------------------------------------------------------------------

namespace Tests\Feature\Contact;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Requests\CustomRequest;
use App\Contact;                        #連絡事項クラスの宣言
use App\User;                           #ユーザークラスの宣言
use App\Work;                           #勤怠クラスの宣言
use DB;                                 #DBクラスの宣言

class ErrorContactControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testindex()
    {
        # 1-1.ページ遷移エラー確認(連絡事項一覧)
        $response = $this->get('/contact');
        $response->assertStatus(302);
    }

    public function testcreate()
    {
        # 2-1.ページ遷移エラー確認(連絡事項登録)
        $response = $this->get('/contact/create');
        $response->assertStatus(302);
    }

    public function teststore()
    {
        # 3-1.バリデーションエラー(1つづつnull設定にして全カラムをチェック)


        # 3-2.DB登録エラー(1つづつnull設定にして全カラムをチェック)
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
        $results          = $contact->save();
        $results          = false;
        if ($results != true){
            $errer_messege = "システムエラーが発生しました。管理者にご連絡ください。";
        }
        $this->assertEquals('システムエラーが発生しました。管理者にご連絡ください。', $errer_messege);
        # 3-3.slack送信エラー(引数0)

        # 3-4.slack送信エラー(不正な引数値)



        DB::table('contacts')->delete();
    }

    public function testshow()
    {
        # 4-1.ページ遷移エラー確認(連絡事項詳細)
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
        $response = $this->get('/contact/1');
        $response->assertStatus(302);                               #ログインしないでエラー確認

        # 4-2.レコード取得エラー(レコードnull)
        DB::table('contacts')->delete();
        $contact_record   = DB::table('contacts')
                            ->select('id')
                            ->where('id', 1)
                            ->first();
        if ($contact_record == null){
            $errer_messege = "システムエラーが発生しました。管理者にご連絡ください。";
        }
        $this->assertEquals('システムエラーが発生しました。管理者にご連絡ください。', $errer_messege);

        # 4-3.ユーザー情報取得エラー(引数0)

        # 4-4.ユーザー情報取得エラー(不正引数)

    }

    public function testedit()
    {
        # 5-1.ページ遷移エラー確認(連絡事項編集)
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
        $response = $this->get('/contact/$contact->id/edit');
        $response->assertStatus(302);                               #ログインしないでエラー確認

        # 5-2.レコード取得エラー
        DB::table('contacts')->delete();
        $contact_record   = DB::table('contacts')
                            ->select('id')
                            ->where('id', 1)
                            ->first();
        if ($contact_record == null){
            $errer_messege = "システムエラーが発生しました。管理者にご連絡ください。";
        }
        $this->assertEquals('システムエラーが発生しました。管理者にご連絡ください。', $errer_messege);

        # 5-3.ユーザー情報取得エラー(引数0)

        # 5-4.ユーザー情報取得エラー(不正引数)
    }

    public function testupdate()
    {
        # 6-1.レコード取得エラー
        $contact_record   = DB::table('contacts')
                            ->select('id')
                            ->where('id', 1)
                            ->first();
        if ($contact_record == null){
            $errer_messege = "システムエラーが発生しました。管理者にご連絡ください。";
        }
        $this->assertEquals('システムエラーが発生しました。管理者にご連絡ください。', $errer_messege);


        # 6-2.DB更新エラー(1つづつnull設定にして全カラムをチェック)
        $user             = factory(User::class)->create();           # データ登録
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

        $results = DB::table('contacts')
                    ->where('id', 1)
                    ->update([
                        'year'    => 2021,
                        'month'   => 1,
                        'day'     => 30,
                        'subject' => 'TEST1',
                        'body'    => 'TEST1',
                    ]);
        $results = false;
        if ($results != true){
            $errer_messege = "システムエラーが発生しました。管理者にご連絡ください。";
        }
        $this->assertEquals('システムエラーが発生しました。管理者にご連絡ください。', $errer_messege);

        DB::table('contacts')->delete();
    }

    public function testdestroy()
    {
        # 7-1.レコード取得エラー
        $contact_record   = DB::table('contacts')
                            ->select('id')
                            ->where('id', 1)
                            ->first();
        if ($contact_record == null){
            $errer_messege = "システムエラーが発生しました。管理者にご連絡ください。";
        }
        $this->assertEquals('システムエラーが発生しました。管理者にご連絡ください。', $errer_messege);

        # 7-2.DBレコード削除エラー
        $results    =  DB::table('contacts')->delete();
        if ($results != true){
            $errer_messege = "システムエラーが発生しました。管理者にご連絡ください。";
            return view('layouts.errer', ['errer_messege' => $errer_messege]);
        }
        $this->assertEquals('システムエラーが発生しました。管理者にご連絡ください。', $errer_messege);
    }
}