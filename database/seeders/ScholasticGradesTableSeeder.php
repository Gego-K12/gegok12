<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\School;
use DB;
use Illuminate\Database\Seeder;

class ScholasticGradesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $schools = School::where('status', 1)->get();

        foreach ($schools as $school) {
            $academic_year = AcademicYear::where([['school_id', $school->id], ['status', 1]])->first();
            DB::table('sc_grade')->Insert([
                'school_id' => $school->id,
                'academic_year_id' => $academic_year->id,
                'grade_name' => 'scholastic',
                'grading_method' => 'cbse',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            DB::table('sc_grade')->Insert([
                'school_id' => $school->id,
                'academic_year_id' => $academic_year->id,
                'grade_name' => 'scholastic',
                'grading_method' => 'passfail',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
