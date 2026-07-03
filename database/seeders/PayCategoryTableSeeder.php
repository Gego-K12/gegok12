<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class PayCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pay_categories')->insert([

            'name' => 'Not Applicable',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

        ]);
        DB::table('pay_categories')->insert([

            'name' => 'User Defined',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

        ]);
        DB::table('pay_categories')->insert([

            'name' => 'On Attendance',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

        ]);
        DB::table('pay_categories')->insert([

            'name' => 'Computation',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

        ]);
        /*DB::table('pay_categories')->insert([

        'name'             => 'Flat Rate',
        'created_at'        => date("Y-m-d H:i:s"),
        'updated_at'        => date("Y-m-d H:i:s"),

        ]);*/
    }
}
