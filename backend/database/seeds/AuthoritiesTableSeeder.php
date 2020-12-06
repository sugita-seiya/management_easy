<?php

use Illuminate\Database\Seeder;

class AuthoritiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('authorities')->insert([
            ['authority_name' => '管理者'],
            ['authority_name' => '一般'],
        ]);
    }
}
