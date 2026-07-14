<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Services;

use App\Helpers\SiteHelper;
use App\Http\Resources\StudentMark as StudentMarkResource;
use App\Models\StandardLink;
use App\Models\Subject;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Log;

class StudentService
{
    public function getStudentMark($studentId, $examId)
    {
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        if (class_exists('Gegok12\Exam\Models\Mark')) {
            $mark = \Gegok12\Exam\Models\Mark::where('exam_id', $examId)
                ->where('user_id', $studentId)
                ->where('school_id', Auth::user()->school_id)
                ->where('academic_year_id', $academic_year->id)
                ->get();

            StudentMarkResource::withoutWrapping();

            return StudentMarkResource::collection($mark)->groupBy('exam.name');
        }
    }

    public function getAllMarks($studentId)
    {
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        if (class_exists('Gegok12\Exam\Models\Mark')) {
            $mark = \Gegok12\Exam\Models\Mark::where('user_id', $studentId)
                ->where('school_id', Auth::user()->school_id)
                ->where('academic_year_id', $academic_year->id)
                ->get();

            StudentMarkResource::withoutWrapping();

            return StudentMarkResource::collection($mark)->groupBy('exam.name');
        }
    }

    public function compareMarks($studentId, $examIdOne, $examIdTwo, $standardId): ?View
    {
        try {
            $standard = StandardLink::where('id', $standardId)->first();

            $standard_id = $standard->standard_id;
            $section_id = $standard->section_id;

            $subjects = Subject::where('standard_id', $standard_id)
                ->where('section_id', $section_id)
                ->pluck('name')
                ->toArray();

            $marksone = [];
            $markstwo = [];
            $examone = [];
            $examtwo = [];
            $examOneAverage = [];
            $examTwoAverage = [];

            if (class_exists('Gegok12\Exam\Models\Mark')) {
                $marksone = \Gegok12\Exam\Models\Mark::where('user_id', $studentId)
                    ->where('exam_id', $examIdOne)
                    ->pluck('obtained_marks')
                    ->toArray();

                $markstwo = \Gegok12\Exam\Models\Mark::where('user_id', $studentId)
                    ->where('exam_id', $examIdTwo)
                    ->pluck('obtained_marks')
                    ->toArray();

                $examOneAverage = \Gegok12\Exam\Models\Mark::where([
                    ['standard_id', $standardId],
                    ['exam_id', $examIdOne],
                ])
                    ->groupBy('subject_id')
                    ->selectRaw('round(avg(obtained_marks)) as avg')
                    ->pluck('avg');

                $examTwoAverage = \Gegok12\Exam\Models\Mark::where([
                    ['standard_id', $standardId],
                    ['exam_id', $examIdTwo],
                ])
                    ->groupBy('subject_id')
                    ->selectRaw('round(avg(obtained_marks)) as avg')
                    ->pluck('avg');
            }

            if (class_exists('Gegok12\Exam\Models\Exam')) {
                $examone = \Gegok12\Exam\Models\Exam::where('standard_id', $standardId)
                    ->where('id', $examIdOne)
                    ->pluck('name')
                    ->toArray();

                $examtwo = \Gegok12\Exam\Models\Exam::where('standard_id', $standardId)
                    ->where('id', $examIdTwo)
                    ->pluck('name')
                    ->toArray();
            }

            return view('/admin/exammark/process', [
                'subjects' => $subjects,
                'marksone' => $marksone,
                'markstwo' => $markstwo,
                'examone' => $examone,
                'examtwo' => $examtwo,
                'examOneAverage' => $examOneAverage,
                'examTwoAverage' => $examTwoAverage,
            ]);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        return null;
    }
}
