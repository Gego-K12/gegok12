<?php

namespace Database\Seeders;

use App\Models\School;
use DB;
use Illuminate\Database\Seeder;

class SectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schools = School::where('status', 1)->get();

        foreach ($schools as $school) {
            $sections = ['A', 'B'];

            foreach ($sections as $section) {
                DB::table('sections')->Insert([
                    'school_id' => $school->id,
                    'name' => $section,
                    'status' => '1',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
