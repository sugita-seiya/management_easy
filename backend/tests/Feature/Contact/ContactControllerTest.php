<?php
#----------------------------------------------------------------------------------
# ContactControllerテスト
# 実行コマンド: ./vendor/bin/phpunit tests/Feature/Contact/ContactControllerTest.php
#----------------------------------------------------------------------------------

namespace Tests\Feature\Contact;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Contact;                        #contactクラスの宣言
use App\User;                           #contactクラスの宣言
use DB;                                 #DBクラスの宣言

class ContactControllerTest extends TestCase
{
    // use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    # DBからデータを取得してindexページへ遷移出来ること
    public function testindex()
    {
        # ログインユーザー作成
        $user = factory(User::class)->create();
        // $user = factory(User::class)->create([
        //     'password' => bcrypt('aaaaaaaa')
        // ]);

        # 連絡事項を全て取得出来ていること
        $contacts   = Contact::all();
        $this->assertNotEmpty($contacts);

        # ログイン状態で任意ページへアクセスチェック
        $response = $this->actingAs($user)->get('/contact');
        $this->assertEquals(200, $response->status());
    }

    public function testcreate()
    {
        # ログインユーザー作成
        $user = factory(User::class)->create();
        // $user = factory(User::class)->create([
        //     'password' => bcrypt('aaaaaaaa')
        // ]);

        # ログイン状態で任意ページへアクセスチェック
        $response = $this->actingAs($user)->get('/contact/create');
        $this->assertEquals(200, $response->status());
    }

    public function testupdate()
    {
        # 1.連絡事項更新レコードID取得確認
        $contact   = DB::table('contacts')->where('id','1')->first();
        $this->assertEquals(1, $contact->id);                           #ID=1である事

        # 2. 連絡事項更新レコード更新確認
        $results = DB::table('contacts')
                    ->where('id', $contact->id)
                    ->update([
                        'year'    => '2021',
                        'month'   => '1',
                        'day'     => '25',
                        'subject' => 'TEST1',
                        'body'    => 'TEST1',
                    ]);
        // $this->assertEquals(1, $results);                               #更新レコード数が1である事
        $contact   = DB::table('contacts')->where('id','1')->first();
        $this->assertEquals('2021', $contact->year);                    #更新されていることを確認
        $this->assertEquals('1', $contact->month);                      #更新されていることを確認
        $this->assertEquals('25', $contact->day);                       #更新されていることを確認
        $this->assertEquals('TEST1', $contact->subject);                #更新されていることを確認
        $this->assertEquals('TEST1', $contact->body);                   #更新されていることを確認

        # 3.ページ遷移確認(連絡事項一覧)
        $user = factory(User::class)->create([
            'password' => bcrypt('aaaaaaaa')
        ]);
        $response = $this->actingAs($user)->get(route('work.index'));
        $response->assertStatus(200);
    }
}
