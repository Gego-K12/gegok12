<?php

namespace App\Schoolplus;

use App\Helpers\SiteHelper;
use App\Http\Resources\StudentMark as StudentMarkResource;
use App\Models\Exam;
use App\Models\StandardLink;
use App\Models\Subject;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Log;

/**
 * Class StudentService
 *
 * Service class responsible for handling
 * student-related academic operations such as:
 * - Fetching student details
 * - Retrieving marks and exam performance
 * - Comparing exam results
 *
 * This service is designed to be consumed by
 * controllers, Livewire components, or APIs.
 */
class StudentService
{
    /**
     * Test method to verify service availability.
     *
     * @return string
     */
    public function test()
    {
        return 'this works';
    }

    /**
     * Retrieve a student user by ID.
     *
     * Ensures the user belongs to the student user group.
     *
     * @param  int|string  $studentId
     * @return User
     *
     * @throws ModelNotFoundException
     */
    private function getStudentById($studentId)
    {
        $user = User::find($studentId);

        if ($user->usergroup_id == 6) {
            return $user;
        }

        throw new ModelNotFoundException('Student not found by ID '.$studentId);
    }

    /**
     * Get basic student information.
     *
     * @param  int|string  $studentId
     * @return mixed
     */
    public function getBasicInfo($studentId)
    {
        // return $this->getStudentById($studentId);
    }

    /**
     * Get student attendance information.
     *
     * Period examples:
     * - today
     * - this week
     * - this month
     * - academic year so far
     *
     * @param  int|string  $studentId
     * @param  string  $period
     * @return mixed
     */
    public function getAttendanceInfo($studentId, $period)
    {
        //
    }

    /**
     * Get classroom-related information for a student.
     *
     * Example data:
     * - Standard
     * - Section
     * - Number of students
     * - Class teacher
     * - Average attendance rate
     *
     * @param  int|string  $studentId
     * @return mixed
     */
    public function getClassRoomInfo($studentId)
    {
        //
    }

    /**
     * Get list of teachers associated with the student.
     *
     * @param  int|string  $studentId
     * @return mixed
     */
    public function getTeachersList($studentId)
    {
        //
    }

    /**
     * Get list of subjects associated with the student.
     *
     * @param  int|string  $studentId
     * @return mixed
     */
    public function getSubjectsList($studentId)
    {
        //
    }

    /**
     * Get list of upcoming classroom events.
     *
     * @param  int|string  $studentId
     * @return mixed
     */
    public function upcomingClassroomEventsList($studentId)
    {
        //
    }

    /**
     * Get list of past classroom events.
     *
     * @param  int|string  $studentId
     * @return mixed
     */
    public function pastClassroomEventsList($studentId)
    {
        //
    }

    /**
     * Get lesson plan for a given day.
     *
     * Day examples:
     * - today
     * - yesterday
     * - tomorrow
     *
     * @param  int|string  $studentId
     * @param  string  $day
     * @return mixed
     */
    public function getLessonPlanForDay($studentId, $day)
    {
        //
    }

    /**
     * Get memos or kudos for a given day.
     *
     * @param  int|string  $studentId
     * @param  string  $day
     * @return mixed
     */
    public function getMemosOrKudos($studentId, $day)
    {
        //
    }

    /**
     * Get marks of a student for a specific exam.
     *
     * Groups marks by exam name.
     *
     * @param  int|string  $studentId
     * @param  int|string  $examId
     * @return Collection
     */
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

    /**
     * Get all marks of a student across exams.
     *
     * Groups marks by exam name.
     *
     * @param  int|string  $studentId
     * @return Collection
     */
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

    /**
     * Compare marks of a student between two exams.
     *
     * Generates comparison data including:
     * - Student marks for both exams
     * - Subject-wise class average
     * - Exam names
     *
     * @param  int|string  $studentId
     * @param  int|string  $examIdOne
     * @param  int|string  $examIdTwo
     * @param  int|string  $standardId
     * @return View
     */
    public function compareMarks($studentId, $examIdOne, $examIdTwo, $standardId)
    {
        try {
            $standard = StandardLink::where('id', $standardId)->first();

            $standard_id = $standard->standard_id;
            $section_id = $standard->section_id;

            $subjects = Subject::where('standard_id', $standard_id)
                ->where('section_id', $section_id)
                ->pluck('name')
                ->toArray();

            $subjects = [];
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
    }
}
