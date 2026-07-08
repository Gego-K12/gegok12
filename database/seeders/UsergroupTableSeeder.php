<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UsergroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('usergroups')->Insert([
            'id' => '1',
            'name' => 'SiteAdmin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('usergroups')->Insert([
            'id' => '2',
            'name' => 'SiteSubadmin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('usergroups')->Insert([
            'id' => '3',
            'name' => 'SchoolAdmin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('usergroups')->Insert([
            'id' => '4',
            'name' => 'SchoolSubadmin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('usergroups')->Insert([
            'id' => '5',
            'name' => 'Teacher',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('usergroups')->Insert([
            'id' => '6',
            'name' => 'Student',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('usergroups')->Insert([
            'id' => '7',
            'name' => 'Parent',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('usergroups')->Insert([
            'id' => '8',
            'name' => 'Librarian',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('usergroups')->Insert([
            'id' => '9',
            'name' => 'OldStudent',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('usergroups')->Insert([
            'id' => '10',
            'name' => 'Receptionist',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('usergroups')->Insert([
            'id' => '11',
            'name' => 'Accountant',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('usergroups')->Insert([
            'id' => '12',
            'name' => 'Stock Keeper',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('usergroups')->Insert([
            'id' => '13',
            'name' => 'Non Teaching',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
