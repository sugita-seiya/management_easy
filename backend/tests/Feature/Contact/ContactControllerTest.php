<?php
#----------------------------------------------------------------------------------
# ContactControllerテスト
# 実行コマンド: ./vendor/bin/phpunit tests/Feature/Contact/ContactControllerTest.php
#----------------------------------------------------------------------------------

namespace Tests\Feature\Contact;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Contact;                           #contactクラスの宣言
use App\User;                           #contactクラスの宣言

class ContactControllerTest extends TestCase
{
    // use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    # DBからデータを取得してindexページへ遷移出来ること
    public function testGetIndex()
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
        $response = $this->actingAs($user)->get(route('contact.index'));
        $this->assertEquals(200, $response->status());
    }
}
