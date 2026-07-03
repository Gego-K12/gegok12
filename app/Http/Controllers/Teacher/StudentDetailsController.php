<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Teacher;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityLog as ActivityLogResource;
use App\Http\Resources\API\BookLending as BookLendingResource;
use App\Http\Resources\AttendanceUser as AttendanceUserResource;
use App\Http\Resources\Discipline as DisciplineResource;
use App\Http\Resources\LeaveHistory as LeaveHistoryResource;
use App\Http\Resources\SiblingListResource;
use App\Http\Resources\UserDetail as UserDetailResource;  // new
use App\Http\Resources\UserDocument as UserDocumentResource;
use App\Http\Resources\UserFees as UserFeesResource; // new
use App\Http\Resources\UserRelation as UserRelationResource;
use App\Http\Resources\UserSibling as UserSiblingResource;
use App\Models\ActivityLog;
// use App\Schoolplus\Student;
use App\Models\Document;
use App\Models\Fee;
use App\Models\Mark;
use App\Models\StudentParentLink;
use App\Models\TeacherLeaveApplication;
use App\Models\User;
use App\Schoolplus\StudentService;
use App\Traits\Common; // new
use App\Traits\LogActivity;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

 // new

class StudentDetailsController extends Controller
{
    use Common;
    //
    use LogActivity;

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($name)
    {
        //
        $user = User::with('studentAcademicLatest')->where('name', $name)->first();
        $parents = $user->parent;
        if (Gate::allows('member', $user)) {
            return view('/teacher/student/show', ['user' => $user, 'parents' => $parents]);
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showDetails($name)
    {
        //
        $users = User::with('userprofile')->where('name', $name)->get();

        $users = UserDetailResource::collection($users);

        return $users;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showRelations($name)
    {
        //
        $student = User::with('userprofile')->where('name', $name)->first();

        $parents = UserRelationResource::collection($student->parents);

        return $parents;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showSiblings($name)
    {
        //
        // $student = User::with('userprofile')->where('name', $name)->get();

        // $siblings = UserSiblingResource::collection($student);

        $student = User::with('userprofile')->where('name', $name)->first();
        $parents = StudentParentLink::where('student_id', $student->id)->pluck('parent_id')->toArray();
        $siblings = StudentParentLink::where('student_id', '!=', $student->id)->whereIn('parent_id', $parents)->get()->unique('student_id');

        $siblings = SiblingListResource::collection($siblings);

        return $siblings;
    }

    public function showActivity($name)
    {
        //
        $user = User::with('userprofile')->where('name', $name)->first();
        if (Gate::allows('member', $user)) {
            $activitylog = ActivityLog::where('subject_id', $user->userprofile->id)->orWhere('subject_id', $user->members[0]['id'])->paginate(5);

            $activitylog = ActivityLogResource::collection($activitylog);

            return $activitylog;
        } else {
            abort(403);
        }
    }

    public function showActivityLog($name)
    {
        //
        $user = User::with('userprofile')->where('name', $name)->first();
        if (Gate::allows('member', $user)) {
            $activitylog = ActivityLog::where('causer_id', $user->userprofile->id)->orWhere('causer_id', $user->members[0]['id'])->paginate(5);

            $activitylog = ActivityLogResource::collection($activitylog);

            return $activitylog;
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showDisciplines($name)
    {
        //
        $student = User::with('disciplineUser', 'disciplineTeacher')->where('name', $name)->first();

        $discipline = DisciplineResource::collection($student->disciplineUser);

        return $discipline;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showAttendance($name)
    {
        //
        $student = User::where('name', $name)->first();

        $attendances = AttendanceUserResource::collection($student->AttendanceUserAbsent);

        return $attendances;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showMedicalHistory($name)
    {
        //
        $student = User::where('name', $name)->first();

        $medicals = [];

        $medicals['height'] = $student->studentAcademicLatest->height;
        $medicals['weight'] = $student->studentAcademicLatest->weight;
        $medicals['medication_problems'] = $student->studentAcademicLatest->medication_problems;
        $medicals['medication_needs'] = $student->studentAcademicLatest->medication_needs;
        $medicals['medication_allergies'] = $student->studentAcademicLatest->medication_allergies;
        $medicals['food_allergies'] = $student->studentAcademicLatest->food_allergies;
        $medicals['other_allergies'] = $student->studentAcademicLatest->other_allergies;
        $medicals['other_medical_information'] = $student->studentAcademicLatest->other_medical_information;

        return $medicals;
    }

    public function showBookLent($name)
    {
        //
        $student = User::with('lending')->where('name', $name)->first();

        $lent = BookLendingResource::collection($student->lending);

        return $lent;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function showmark($name)
    {
        $users = User::with('marks')->where('name', $name)->first();
        $studentId = $users->id;
        $examId = $users->marks[0]['exam_id'];

        return Student::getStudentMark($studentId, $examId);
    }

    public function showAllMark($name)
    {
        $users = User::where('name', $name)->first();

        $studentId = $users->id;

        return Student::getAllMarks($studentId);
    }

    public function compareMarks($name)
    {
        $users = User::with('studentAcademic')->where('name', $name)->get();
        $studentId = $users[0]['id'];
        $standardId = $users[0]['studentAcademicLatest']['standardLink_id'];
        if (class_exists('Gegok12\Exam\Models\Mark')) {
            $exam = \Gegok12\Exam\Models\Mark::where('school_id', Auth::user()->school_id)->where('standard_id', $standardId)->take(2)->orderBy('exam_id', 'DESC')->groupBy('exam_id')->pluck('exam_id')->toArray();
        } else {
            $exam = Mark::where('school_id', Auth::user()->school_id)->where('standard_id', $standardId)->take(2)->orderBy('exam_id', 'DESC')->groupBy('exam_id')->pluck('exam_id')->toArray();
        }
        $examIdOne = $exam[0];
        $examIdTwo = $exam[1];

        // return Student::CompareMarks($studentId,$examIdOne,$examIdTwo,$standardId);
        $studentService = new StudentService;

        return $studentService->compareMarks($studentId, $examIdOne, $examIdTwo, $standardId);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function showDocuments($name)
    {
        //
        $user = User::where('name', $name)->first();
        $documents = Document::where('user_id', $user->id)->where('status', 1)->get();

        $documents = UserDocumentResource::collection($documents);

        return $documents;
    }

    public function showFees($name)
    {
        $student = User::where('name', $name)->first();

        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $fees = Fee::where([['school_id', $school_id], ['academic_year_id', $academic_year->id]])->where('standardLink_id', $student->studentAcademicLatest->standardLink_id)->orWhere('standardLink_id', null)->orderBy('start_date', 'DESC')->paginate(5);

        $feepayments = UserFeesResource::collection($fees);

        return $feepayments;
    }

    public function showLeaveHistory($name)
    {

        $user = User::where('name', $name)->first();
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $leave = TeacherLeaveApplication::where([
            ['user_id', $user->id],
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
        ])->paginate(5);
        $leave = LeaveHistoryResource::collection($leave);

        return $leave;
    }
}
