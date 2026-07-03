<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('countries')->Insert([
            'name' => 'Afghanistan',
            'short_name' => 'AF',
            'iso_code' => 'AFG',
            'tel_prefix' => '93',
            'status' => '0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('countries')->Insert([
            'name' => 'Albania',
            'short_name' => 'AL',
            'iso_code' => 'ALB',
            'tel_prefix' => '355',
            'status' => '0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('countries')->Insert([
            'name' => 'Argentina',
            'short_name' => 'AR',
            'iso_code' => 'ARG',
            'tel_prefix' => '54',
            'status' => '0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('countries')->Insert([
            'name' => 'Australia',
            'short_name' => 'AU',
            'iso_code' => 'AUS',
            'tel_prefix' => '61',
            'status' => '0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('countries')->Insert([
            'name' => 'China',
            'short_name' => 'CH',
            'iso_code' => 'CHN',
            'tel_prefix' => '86',
            'status' => '0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('countries')->Insert([
            'name' => 'Egypt',
            'short_name' => 'EG',
            'iso_code' => 'EGY',
            'tel_prefix' => '20',
            'status' => '0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('countries')->Insert([
            'name' => 'India',
            'short_name' => 'IN',
            'iso_code' => 'IND',
            'tel_prefix' => '91',
            'status' => '1',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('countries')->Insert([
            'name' => 'Malaysia',
            'short_name' => 'MA',
            'iso_code' => 'MYS',
            'tel_prefix' => '60',
            'status' => '0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('countries')->Insert([
            'name' => 'Switzerland',
            'short_name' => 'SW',
            'iso_code' => 'CHE',
            'tel_prefix' => '41',
            'status' => '0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
