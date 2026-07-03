<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * For open-source single school version, we create one unlimited plan
     *
     * @return void
     */
    public function run()
    {
        // Single unlimited plan for open-source version
        DB::table('plans')->insert([
            'cycle' => '36500', // 100 years
            'name' => 'unlimited',
            'display_name' => 'UNLIMITED',
            'amount' => '0',
            'no_of_members' => '999999',
            'no_of_events' => '999999',
            'no_of_folders' => '999999',
            'no_of_files' => '999999',
            'no_of_videos' => '999999',
            'no_of_bulletins' => '999999',
            'no_of_groups' => '999999',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
