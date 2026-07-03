<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('roles')->insert([
            'id' => '1',
            'name' => 'leave_applier',
            'display_name' => 'Leave Applier',
            'description' => 'Leave Applier',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('roles')->insert([
            'id' => '2',
            'name' => 'leave_checker',
            'display_name' => 'Leave Checker',
            'description' => 'Leave Checker',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('roles')->insert([
            'id' => '3',
            'name' => 'principal',
            'display_name' => 'Principal',
            'description' => 'Principal',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('roles')->insert([
            'id' => '4',
            'name' => 'student_leave_checker',
            'display_name' => 'Student Leave Checker',
            'description' => 'Student Leave Checker',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('roles')->insert([
            'id' => '5',
            'name' => 'class_coordinator',
            'display_name' => 'Class Coordinator',
            'description' => 'Class Coordinator',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('roles')->insert([
            'id' => '6',
            'name' => 'transport_coordinator',
            'display_name' => 'Transport Coordinator',
            'description' => 'Transport Coordinator',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('roles')->insert([
            'id' => '7',
            'name' => 'transport_driver',
            'display_name' => 'Transport Driver',
            'description' => 'Transport Driver',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
