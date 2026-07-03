<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\School;
use DB;
use Illuminate\Database\Seeder;

class LeaveTypesTableSeeder extends Seeder
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
            DB::table('leave_types')->Insert([
                'school_id' => $school->id,
                'academic_year_id' => $academic_year->id,
                'name' => 'Earned Leave or Privilege Leave',
                'max_no_of_days' => 2,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            DB::table('leave_types')->Insert([
                'school_id' => $school->id,
                'academic_year_id' => $academic_year->id,
                'name' => 'Casual Leave',
                'max_no_of_days' => 1,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            DB::table('leave_types')->Insert([
                'school_id' => $school->id,
                'academic_year_id' => $academic_year->id,
                'name' => 'Sick Leave or Medical Leave',
                'max_no_of_days' => 1,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            DB::table('leave_types')->Insert([
                'school_id' => $school->id,
                'academic_year_id' => $academic_year->id,
                'name' => 'Maternity Leave',
                'max_no_of_days' => 45,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            DB::table('leave_types')->Insert([
                'school_id' => $school->id,
                'academic_year_id' => $academic_year->id,
                'name' => 'Quarantine Leave',
                'max_no_of_days' => 5,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            DB::table('leave_types')->Insert([
                'school_id' => $school->id,
                'academic_year_id' => $academic_year->id,
                'name' => 'Study Leave or Sabbatical Leave',
                'max_no_of_days' => 7,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
