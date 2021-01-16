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

class ErrorContactControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testindex()
    {
        # 1.ページ遷移エラー確認(連絡事項一覧)
        $response = $this->get('/contact');
        $response->assertStatus(302);
    }
}
