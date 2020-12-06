<?php

use Illuminate\Database\Seeder;

class Work_sectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('work_sections')->insert([
            ['section_name' => '出勤'],
            ['section_name' => '法定休日'],
            ['section_name' => '法定外休日'],
        ]);
    }
}
