<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use App\Models\School;

class AcademicYearsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schools = School::where('status', 1)->get();

        $currentYear = date('Y');

        $currentAcademicYear = $currentYear . '-' . ($currentYear + 1);
        $nextAcademicYear    = ($currentYear + 1) . '-' . ($currentYear + 2);

        foreach ($schools as $school) {

            // Current Academic Year
            DB::table('academic_years')->insert([
                'school_id'   => $school->id,
                'name'        => $currentAcademicYear,
                'description' => 'This is Current Academic Year',
                'start_date'  => $currentYear . '-06-01',
                'end_date'    => ($currentYear + 1) . '-04-30',
                'status'      => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            // Next Academic Year
            DB::table('academic_years')->insert([
                'school_id'   => $school->id,
                'name'        => $nextAcademicYear,
                'description' => 'This is Next Academic Year',
                'start_date'  => ($currentYear + 1) . '-06-01',
                'end_date'    => ($currentYear + 2) . '-04-30',
                'status'      => 2,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}