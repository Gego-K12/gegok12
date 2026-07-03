<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api;

use App\Events\Notification\SingleNotificationEvent;
use App\Events\SinglePushEvent;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\StudentLeaveAddRequest;
use App\Http\Requests\StudentLeaveUpdateRequest;
use App\Http\Resources\API\Leave as LeaveResource;
use App\Models\AbsentReason;
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

class LeaveController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($student_id)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $application = TeacherLeaveApplication::where([
            ['user_id', $student_id],
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
        ])->get();

        $application = LeaveResource::collection($application);

        return response()->json([
            'success' => true,
            'message' => 'Leave List',
            'data' => $application,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        $reasonlist = AbsentReason::where('status', 1)->get();

        return response()->json([
            'success' => true,
            'message' => 'Reason List',
            'data' => $reasonlist,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(StudentLeaveAddRequest $request, $student_id)
    {
        //
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $user = User::find($student_id);

            $leave = new TeacherLeaveApplication;

            $leave->school_id = $school_id;
            $leave->academic_year_id = $academic_year->id;
            $leave->standardLink_id = $user->studentAcademicLatest->standardLink_id;
            $leave->user_id = $student_id;
            $leave->from_date = date('Y-m-d H:i:s', strtotime($request->from_date));
            $leave->to_date = date('Y-m-d H:i:s', strtotime($request->to_date));
            $leave->reason_id = $request->reason_id;
            $leave->remarks = $request->remarks;
            $leave->session = $request->session;
            $leave->status = 'pending';

            $leave->save();

            $student = User::where('id', $student_id)->first();

            $standardLink = StandardLink::where('id', $student->studentAcademicLatest->standardLink_id)->first();
            $teacher = User::where('id', $standardLink->class_teacher_id)->first();

            $array = [];

            $array['school_id'] = Auth::user()->school_id;
            $array['user_id'] = $teacher->id;
            $array['message'] = $student->FullName.'Applied leave';
            $array['type'] = 'leave';

            event(new SinglePushEvent($array));

            $data = [];

            $data['user'] = $teacher;
            $data['details'] = trans('notification.user_apply_leave', ['user' => $student->FullName]);

            event(new SingleNotificationEvent($data));

            $message = trans('messages.add_success_msg', ['module' => 'Leave Application']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $leave,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_LEAVEAPPLICATION,
                $message
            );

            return response()->json([
                'success' => true,
                'message' => $message,
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $array = [];

        $leave = TeacherLeaveApplication::where('id', $id)->first();

        $array['from_date'] = date('d-m-Y H:i:s', strtotime($leave->from_date));
        $array['to_date'] = date('d-m-Y H:i:s', strtotime($leave->to_date));
        $array['reason_id'] = $leave->reason_id;
        $array['remarks'] = $leave->remarks;
        $array['session'] = $leave->session;

        return response()->json([
            'success' => true,
            'message' => 'Show Leave',
            'data' => $array,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(StudentLeaveUpdateRequest $request, $id)
    {
        //
        try {
            $leave = TeacherLeaveApplication::where('id', $id)->first();

            $leave->from_date = date('Y-m-d H:i:s', strtotime($request->from_date));
            $leave->to_date = date('Y-m-d H:i:s', strtotime($request->to_date));
            $leave->reason_id = $request->reason_id;
            $leave->remarks = $request->remarks;
            $leave->session = $request->session;
            $leave->status = 'pending';

            $leave->save();

            $student = User::where('id', $leave->user_id)->first();

            $standardLink = StandardLink::where('id', $student->studentAcademicLatest->standardLink_id)->first();
            $teacher = User::where('id', $standardLink->class_teacher_id)->first();

            $array = [];

            $array['school_id'] = Auth::user()->school_id;
            $array['user_id'] = $teacher->id;
            $array['message'] = $student->FullName.'Applied leave';
            $array['type'] = 'leave';

            event(new SinglePushEvent($array));

            $data = [];

            $data['user'] = $teacher;
            $data['details'] = trans('notification.user_update_leave', ['user' => $student->FullName]);

            event(new SingleNotificationEvent($data));

            $message = trans('messages.update_success_msg', ['module' => 'Leave Application']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $leave,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_LEAVEAPPLICATION,
                $message
            );

            return response()->json([
                'success' => true,
                'message' => $message,
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        try {
            $leave = TeacherLeaveApplication::where('id', $id)->first();

            $leave->status = 'cancelled';
            $leave->save();

            $leave->delete();

            $message = trans('messages.delete_success_msg', ['module' => 'Leave Application']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $leave,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_LEAVEAPPLICATION,
                $message
            );

            return response()->json([
                'success' => true,
                'message' => $message,
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
