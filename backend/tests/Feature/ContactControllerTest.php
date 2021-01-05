<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        // `users` テーブルにデータを作成 (Tips参照)
        // factory(User::class)->create([
        //     'f_name' => '杉田',
        //     'r_name' => 'セイヤ',
        //     'email' => 'user1@example.com',
        //     'work_system_id' => '1',
        //     'authorty_id' => '2',
        // ]);

        // GET リクエスト
        $response = $this->get('/contact');

        $response->assertStatus(302);  # ステータスコードが 200
        // $response = $this->get('/');

        // $response->assertStatus(200);
    }
}
