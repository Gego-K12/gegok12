<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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
        $path = database_path('sql/countries.sql');

        if (File::exists($path)) {
            DB::unprepared(File::get($path));
        } else {
            echo "SQL file not found!";
        }
    }
}
