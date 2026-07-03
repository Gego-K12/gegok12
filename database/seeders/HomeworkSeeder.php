<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Homework;
use App\Models\HomeworkApproval;
use App\Models\School;
use App\Models\StandardLink;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class HomeworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (env('APP_ENV') == 'local' || env('APP_ENV') == 'development') {
            $schools = School::where('status', 1)->get();
            foreach ($schools as $school) {
                $academic_year = AcademicYear::where([['school_id', $school->id], ['status', 1]])->first();
                $stds = StandardLink::where([['school_id', $school->id], ['academic_year_id', $academic_year->id]])->get();

                foreach ($stds as $std) {
                    $subjects = Subject::where([['school_id', $std->school_id], ['academic_year_id', $std->academic_year_id], ['standard_id', $std->standard_id], ['section_id', $std->section_id]])->get();

                    foreach ($subjects as $key => $subject) {

                        $homework = Homework::factory()->create([
                            'school_id' => $subject->school_id,
                            'academic_year_id' => $subject->academic_year_id,
                            'standardLink_id' => $std->id,
                            'subject_id' => $subject->id,
                            'teacher_id' => $std->class_teacher_id,
                            // 'title'             => $subject->name.''.'Unit-1',
                        ]);
                        HomeworkApproval::factory()->create([
                            'homework_id' => $homework->id,
                        ]);
                    }
                }
            }
        }
    }
}
