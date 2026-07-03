<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Teacher;

use App\Events\Notification\SingleNotificationEvent;
use App\Events\SinglePushEvent;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveApproveRequest;
use App\Http\Resources\Teacher\StudentLeave as StudentLeaveResource;
use App\Models\StandardLink;
use App\Models\TeacherLeaveApplication;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Log;

class StudentLeaveController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function indexList($status)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $class_teacher = StandardLink::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
            ['class_teacher_id', Auth::id()],
        ])->pluck('id')->toArray();

        $application = TeacherLeaveApplication::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
        ])->where('status', $status)->whereIn('standardLink_id', $class_teacher)->orderBy('id', 'DESC')->paginate(10);

        $application = StudentLeaveResource::collection($application);

        return $application;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        return view('/teacher/studentleave/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function list($id)
    {
        //
        $array = [];
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $leave = TeacherLeaveApplication::where('id', $id)->first();

        $array['name'] = $leave->teacher->FullName;
        $array['from_date'] = date('d-m-Y H:i:s', strtotime($leave->from_date));
        $array['to_date'] = date('d-m-Y H:i:s', strtotime($leave->to_date));
        $array['reason_id'] = $leave->absentReason->title;
        $array['remarks'] = $leave->remarks;

        return $array;
    }

    public function approveCreate($id)
    {
        //
        $leave = TeacherLeaveApplication::where('id', $id)->first();

        return view('/teacher/studentleave/approvecreate', ['leave' => $leave]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function approveStore(LeaveApproveRequest $request, $id)
    {
        //
        try {
            $leave = TeacherLeaveApplication::where('id', $id)->first();

            $leave->comments = $request->comments;
            $leave->approved_by = Auth::id();
            $leave->approved_on = date('Y-m-d');
            $leave->status = 'approved';

            $leave->save();

            $message = trans('messages.approve_success_msg', ['module' => 'Leave Application']);

            $student = User::where('id', $leave->user_id)->first();
            foreach ($student->parents as $parent) {
                $array = [];

                $array['school_id'] = $leave->school_id;
                $array['user_id'] = $parent->userParent->id;
                $array['message'] = $message;
                $array['type'] = 'leave';

                event(new SinglePushEvent($array));
            }

            $data = [];

            $user = User::where('id', $leave->user_id)->first();
            $data['user'] = $user;
            $data['details'] = trans('notification.approve_success_msg', ['user' => Auth::user()->FullName, 'module' => 'Leave Application']);

            event(new SingleNotificationEvent($data));

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $leave,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_APPROVE_LEAVEAPPLICATION,
                $message
            );
            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function rejectCreate($id)
    {
        //
        $leave = TeacherLeaveApplication::where('id', $id)->first();

        return view('/teacher/studentleave/rejectcreate', ['leave' => $leave]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function rejectStore(LeaveApproveRequest $request, $id)
    {
        //
        try {
            $leave = TeacherLeaveApplication::where('id', $id)->first();

            $leave->comments = $request->comments;
            $leave->approved_by = Auth::id();
            $leave->approved_on = date('Y-m-d');
            $leave->status = 'cancelled';

            $leave->save();

            $message = trans('messages.reject_success_msg', ['module' => 'Leave Application']);

            $student = User::where('id', $leave->user_id)->first();
            foreach ($student->parents as $parent) {
                $array = [];

                $array['school_id'] = $leave->school_id;
                $array['user_id'] = $parent->userParent->id;
                $array['message'] = $message;
                $array['type'] = 'leave';

                event(new SinglePushEvent($array));
            }

            $data = [];

            $user = User::where('id', $leave->user_id)->first();
            $data['user'] = $user;
            $data['details'] = trans('notification.reject_success_msg', ['user' => Auth::user()->FullName, 'module' => 'Leave Application']);

            event(new SingleNotificationEvent($data));

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $leave,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_REJECT_LEAVEAPPLICATION,
                $message
            );
            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
