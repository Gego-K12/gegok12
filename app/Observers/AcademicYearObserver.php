<?php

namespace App\Observers;

use App\Models\AcademicYear;
use App\Models\Grade;
use Auth;

class AcademicYearObserver
{
    /**
     * Handle the AcademicYear "created" event.
     */
    public function created(AcademicYear $academicYear): void
    {

        if (config('gexam.enabled', false)) { // new
            if ($academicYear) {
                $cbseGrades = [
                    ['A1', '90', '100'],
                    ['A2', '81', '89'],
                    ['B1', '71', '80'],
                    ['B2', '61', '70'],
                    ['C1', '51', '60'],
                    ['C2', '41', '50'],
                    ['D', '36', '40'],
                    ['E - Needs Improvement', '0', '35'],
                ];

                foreach ($cbseGrades as $key => $grade) {
                    $create = [
                        'school_id' => $academicYear->school_id,
                        'academic_year_id' => $academicYear->id,
                        'name' => 'cbse',
                        'grades' => $grade[0],
                        'min_mark' => $grade[1],
                        'max_mark' => $grade[2],
                        'status' => 1,
                        'created_by' => Auth::Id(),
                    ];
                    if (class_exists('Gegok12\Exam\Models\Grade')) {
                        $grade = \Gegok12\Exam\Models\Grade::create($create);
                    }

                }

                // end foreach
                if (class_exists('Gegok12\Exam\Models\Grade')) {
                    // First Grade - Pass
                    \Gegok12\Exam\Models\Grade::create([
                        'school_id' => $academicYear->school_id,
                        'academic_year_id' => $academicYear->id,
                        'name' => 'passfail',
                        'grades' => 'Pass',
                        'min_mark' => 36,
                        'max_mark' => 100,
                        'status' => 1,
                        'created_by' => 1,
                        'updated_by' => 1,
                    ]);

                    // Second Grade - Fail
                    \Gegok12\Exam\Models\Grade::create([
                        'school_id' => $academicYear->school_id,
                        'academic_year_id' => $academicYear->id,
                        'name' => 'passfail',
                        'grades' => 'Fail',
                        'min_mark' => 0,
                        'max_mark' => 35,
                        'status' => 1,
                        'created_by' => 1,
                        'updated_by' => 1,
                    ]);
                }

            }
        }

    }

    /**
     * Handle the AcademicYear "updated" event.
     */
    public function updated(AcademicYear $academicYear): void {}

    /**
     * Handle the AcademicYear "deleted" event.
     */
    public function deleted(AcademicYear $academicYear): void
    {
        //
    }

    /**
     * Handle the AcademicYear "restored" event.
     */
    public function restored(AcademicYear $academicYear): void
    {
        //
    }

    /**
     * Handle the AcademicYear "force deleted" event.
     */
    public function forceDeleted(AcademicYear $academicYear): void
    {
        //
    }
}
