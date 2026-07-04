<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityLog as ActivityLogResource;
use App\Http\Resources\API\BookLending as BookLendingResource;
use App\Http\Resources\LeaveHistory as LeaveHistoryResource;
use App\Http\Resources\TeacherClasses as TeacherClassesResource;
use App\Http\Resources\TeacherDetail as TeacherDetailResource;
use App\Http\Resources\TeacherTimeTable as TeacherTimeTableResource;
use App\Models\ActivityLog;
use App\Models\TeacherLeaveApplication;
use App\Models\User;
use App\Models\Users\TeacherUser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use PDF;

class TeacherShowController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showDetails($name)
    {
        //
        $users = TeacherUser::with('standardLink')->where('name', $name)->get();
        $users = TeacherDetailResource::collection($users);

        return $users;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showTimetable($name)
    {
        //
        $users = TeacherUser::with('teacherlink')->where('name', $name)->get();
        if (count($users[0]['teacherlink']) > 0) {
            $users = TeacherTimeTableResource::collection($users);
        } else {
            $users = null;
        }

        return $users;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showClasses($name)
    {
        //
        $user = TeacherUser::with('teacherlink')->where('name', $name)->first();
        $users = TeacherClassesResource::collection($user->teacherlink);

        return $users;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showClassTeacher($name)
    {
        //
        $user = TeacherUser::with('standardLink')->where('name', $name)->first();
        $array['standard'] = $user->standardLink->StandardName;
        $array['section'] = $user->standardLink->section->name;

        return $array;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showLeaveHistory($name)
    {
        //
        $user = TeacherUser::where('name', $name)->first();
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

    public function showActivity($name)
    {
        //
        $user = TeacherUser::with('userprofile')->where('name', $name)->first();
        $activitylog = ActivityLog::where('causer_id', $user->id)->orWhere('subject_id', $user->members[0]['id'])->paginate(5);
        $activitylog = ActivityLogResource::collection($activitylog);

        return $activitylog;
    }

    public function showActivityLog($name)
    {
        //
        $user = TeacherUser::with('userprofile')->where('name', $name)->first();
        $activitylog = ActivityLog::where('causer_id', $user->userprofile->id)->orWhere('causer_id', $user->members[0]['id'])->paginate(5);
        $activitylog = ActivityLogResource::collection($activitylog);

        return $activitylog;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($name)
    {
        //
        $user = TeacherUser::where('name', $name)->first();

        return view('/admin/teacher/show', ['user' => $user]);
    }

    public function showidcard($name)
    {

        $academic = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $teacher = User::where('name', $name)->first();

        return view('/admin/teacher/showidcard', ['teacher' => $teacher, 'academic' => $academic]);
    }

    public function showprintidcard($name)
    {
        $academic = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $teacher = User::where('name', $name)->first();

        $pdf = PDF::loadView('admin/teacher/show-idcardprint', ['teacher' => $teacher, 'academic' => $academic]);

        return $pdf->stream('result.pdf', ['Attachment' => 0]);
    }

    public function showBookLent($name)
    {

        $teacher = User::with('lending')->where('name', $name)->first();

        $lent = BookLendingResource::collection($teacher->lending);

        return $lent;
    }
}
