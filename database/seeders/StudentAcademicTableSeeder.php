<?php

namespace Database\Seeders;

use App\Models\StudentAcademic;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentAcademicTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $students = User::where('usergroup_id', 6)->get();

        foreach ($students as $student) {
            factory(StudentAcademic::class, 1)->create([
                'school_id' => $student->school_id,

                'user_id' => $student->id,
            ]);
        }
    }
}
