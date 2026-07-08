<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\School;
use DB;
use Illuminate\Database\Seeder;

class NonScholasticGradesTableSeeder extends Seeder
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
            DB::table('non_sc_grade')->Insert([
                'school_id' => $school->id,
                'academic_year_id' => $academic_year->id,
                'grade_name' => '1-5',
                'keys' => '"art_education", "physical_education", "work_education"',
                'grades_details' => '{ "A": "Outstanding", "B": "Above Average", "C": "Needs Improvement"}',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            DB::table('non_sc_grade')->Insert([
                'school_id' => $school->id,
                'academic_year_id' => $academic_year->id,
                'grade_name' => '6-8',
                'keys' => '"thinking_skills", "social_skills", "emotional_skills"',
                'grades_details' => '{ "A": "Outstanding", "B": "Above Average", "C": "Needs Improvement"}',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            DB::table('non_sc_grade')->Insert([
                'school_id' => $school->id,
                'academic_year_id' => $academic_year->id,
                'grade_name' => '9-10',
                'keys' => '"attitude_values", "wellness_education", "service_activities"',
                'grades_details' => '{ "A": "Outstanding", "B": "Above Average", "C": "Needs Improvement"}',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            DB::table('non_sc_grade')->Insert([
                'school_id' => $school->id,
                'academic_year_id' => $academic_year->id,
                'grade_name' => '11-12',
                'keys' => '"thinking_skills", "physical_education", "work_education"',
                'grades_details' => '{ "A": "Outstanding", "B": "Above Average", "C": "Needs Improvement"}',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
