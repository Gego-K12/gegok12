<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\School;
use App\Models\StandardLink;
use App\Models\Subject;
use App\Models\Teacherlink;
use App\Models\TeacherProfile;
use Illuminate\Database\Seeder;

class TeacherTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $schools = School::where('status', 1)->get();
        if (env('APP_ENV') == 'local' || env('APP_ENV') == 'development') {
            foreach ($schools as $school) {
                $academic_year = AcademicYear::where([['school_id', $school->id], ['status', 1]])->first();
                $stds = StandardLink::where([['school_id', $school->id], ['academic_year_id', $academic_year->id]])->get();

                foreach ($stds as $std) {
                    $subjects = Subject::where([['school_id', $std->school_id], ['academic_year_id', $std->academic_year_id], ['standard_id', $std->standard_id], ['section_id', $std->section_id]])->get();

                    foreach ($subjects as $key => $subject) {
                        if ($key == 0) {
                            Teacherlink::factory(1)->create([
                                'school_id' => $subject->school_id,
                                'academic_year_id' => $subject->academic_year_id,
                                'standardLink_id' => $std->id,
                                'subject_id' => $subject->id,
                                'teacher_id' => $std->class_teacher_id,
                            ]);
                        } else {
                            $subjectName = Subject::find($subject->id)->name;
                            $subjectTeachers = TeacherProfile::where([['school_id', $subject->school_id], ['academic_year_id', $subject->academic_year_id], ['specialization', $subjectName]])->get()->toArray();
                            $teacher_id = ! empty($subjectTeachers) ? $subjectTeachers[array_rand($subjectTeachers)]['user_id'] : null;

                            if (is_null($teacher_id)) {
                                $allTeachers = TeacherProfile::where([['school_id', $subject->school_id], ['academic_year_id', $subject->academic_year_id]])->get()->toArray();
                                $teacher_id = $allTeachers[array_rand($allTeachers, 1)]['user_id'];
                            }

                            Teacherlink::factory(1)->create([
                                'school_id' => $subject->school_id,
                                'academic_year_id' => $subject->academic_year_id,
                                'standardLink_id' => $std->id,
                                'subject_id' => $subject->id,
                                'teacher_id' => $teacher_id,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
