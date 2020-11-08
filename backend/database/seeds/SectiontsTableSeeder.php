<?php

use Illuminate\Database\Seeder;

class SectiontsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sections')->insert([
            'section_name' => '出勤',
        ]);
        DB::table('sections')->insert([
            'section_name' => '公休',
        ]);
        DB::table('sections')->insert([
            'section_name' => '有給',
        ]);
        DB::table('sections')->insert([
            'section_name' => '欠勤',
        ]);
    }
}
