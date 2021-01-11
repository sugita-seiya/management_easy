<?php
#---------------------------------------------------------------------------
# DBカラムチェックテスト
# 実行コマンド: ./vendor/bin/phpunit tests/Feature/Database/DatabaseTest.php
#---------------------------------------------------------------------------

namespace Tests\Feature\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;          //DBスキーマファザード

class DatabaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    //contactsテーブルのカラム確認テスト
    public function test_db_contacts()
    {
        $this->assertTrue(
            Schema::hasColumns('contacts', [
                'id',
                'year' ,
                'month',
                'day',
                'subject',
                'body',
                'user_id'
            ]),
            1
        );
    }
    //authoritiesテーブルのカラム確認テスト
    //usersテーブルのカラム確認テスト
    public function test_db_users()
    {
        $this->assertTrue(
            Schema::hasColumns('users', [
                'id',
                'f_name' ,
                'r_name',
                'email',
                'email_verified_at',
                'password',
                'remember_token',
                'work_system_id',
                'authorty_id'
            ]),
            1
        );
    }
    //work_sectionsテーブルのカラム確認テスト
    //work_systesテーブルのカラム確認テスト
    //worksテーブルのカラム確認テスト
    public function test_db_works()
    {
        $this->assertTrue(
            Schema::hasColumns('works', [
                'id',
                'year' ,
                'month',
                'day',
                'workstart',
                'workend',
                'breaktime',
                'total_worktime',
                'remark',
                'approval_flg',
                'work_section_id',
                'user_id'
            ]),
            1
        );
    }
}
