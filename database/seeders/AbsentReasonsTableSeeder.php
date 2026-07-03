<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class AbsentReasonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $absentReasons = ['Health Issue', 'Family Functions', 'Personal Work', 'Others'];

        foreach ($absentReasons as $reason) {
            DB::table('absent_reasons')->Insert([
                'title' => $reason,
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
