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
use App\Http\Requests\LeaveAddRequest;
use App\Http\Requests\LeaveApproveRequest;
use App\Http\Requests\LeaveEditRequest;
use App\Http\Resources\Teacher\Leave as LeaveResource;
use App\Models\AbsentReason;
use App\Models\LeaveType;
use App\Models\TeacherLeaveApplication;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Carbon\Carbon;
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
    public function list(Request $request)
    {

        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        if (Auth::user()->hasRole('leave_applier')) {
            $application = TeacherLeaveApplication::where([
                ['user_id', Auth::id()],
                ['school_id', $school_id],
                ['academic_year_id', $academic_year->id],
            ]);
            if ($request->status != null) {
                $application = $application->where('status', $request->status);
            }
            $application = $application->orderBy('id', 'DESC')->paginate(10);
        } elseif (Auth::user()->hasRole('leave_checker')) {
            $teacherprofiles = TeacherProfile::where([
                ['school_id', $school_id],
                ['academic_year_id', $academic_year->id],
                ['reporting_to', Auth::id()],
            ])->pluck('user_id')->toArray();

            $application = TeacherLeaveApplication::where([
                ['school_id', $school_id],
                ['academic_year_id', $academic_year->id],
                ['status', 'pending'],
            ])->whereIn('user_id', $teacherprofiles);
            if (\Request::getQueryString() != null) {
                if ($request->showOther == 'true') {
                    $application = TeacherLeaveApplication::where([
                        ['school_id', $school_id],
                        ['academic_year_id', $academic_year->id],
                        ['status', '!=', 'pending'],
                    ])->whereIn('user_id', $teacherprofiles);

                    /* $application = $application->orWhere([
                         ['school_id',$school_id],
                         ['academic_year_id',$academic_year->id],
                         ['status','approved']
                     ])->whereIn('user_id',$teacherprofiles)->orWhere([
                         ['school_id',$school_id],
                         ['academic_year_id',$academic_year->id],
                         ['status','cancelled']
                     ])->whereIn('user_id',$teacherprofiles);*/
                }
            }
            $application = $application->orderBy('id', 'DESC')->paginate(10);
        }

        $application = LeaveResource::collection($application);

        return $application;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function pendingCount()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $pending_count = SiteHelper::getPendingLeaveCount($school_id, $academic_year->id, Auth::id());

        return $pending_count;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $query = \Request::getQueryString();
        $pending_count = SiteHelper::getPendingLeaveCount($school_id, $academic_year->id, Auth::id());

        if (Auth::user()->hasRole('leave_applier')) {
            $user_type = 'apply';
        } elseif (Auth::user()->hasRole('leave_checker')) {
            $user_type = 'check';
        }

        return view('teacher/leave/index', ['query' => $query, 'user_type' => $user_type, 'pending_count' => $pending_count]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function myList(Request $request)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $application = TeacherLeaveApplication::where([
            ['user_id', Auth::id()],
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
        ]);
        if ($request->status != null) {
            $application = $application->where('status', $request->status);
        }
        $application = $application->orderBy('id', 'DESC')->paginate(10);

        $application = LeaveResource::collection($application);

        return $application;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function myIndex()
    {
        //
        return view('teacher/leave/myleaves');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function createList()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $array['leavelist'] = LeaveType::where([['school_id', $school_id], ['academic_year_id', $academic_year->id], ['status', 1]])->get();
        $array['reasonlist'] = AbsentReason::where('status', 1)->get();
        $array['from_date'] = Carbon::now()->addHour(1)->format('d-m-Y');
        $array['to_date'] = Carbon::now()->addDay(1)->format('d-m-Y');

        return $array;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('teacher/leave/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(LeaveAddRequest $request)
    {
        //
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $leave = new TeacherLeaveApplication;

            $leave->school_id = $school_id;
            $leave->academic_year_id = $academic_year->id;
            $leave->user_id = Auth::id();
            $leave->from_date = date('Y-m-d H:i:s', strtotime($request->from_date));
            $leave->to_date = date('Y-m-d H:i:s', strtotime($request->to_date));
            $leave->reason_id = $request->reason_id;
            $leave->remarks = $request->remarks;
            $leave->leave_type_id = $request->leave_type_id;
            $leave->session = $request->session;
            $leave->status = 'pending';

            $leave->save();

            $this->LeaveApplyNotification($leave->user_id, 'add');

            $message = trans('messages.add_success_msg', ['module' => 'Leave Application']);

            $data = [];

            $user = User::where('id', Auth::user()->getTeacherDetails()['reporting_to'])->first();
            if ($user->id != Auth::id()) {
                $data['user'] = $user;
                $data['details'] = trans('notification.add_success_msg', ['user' => Auth::user()->FullName, 'module' => 'Leave Application']);

                event(new SingleNotificationEvent($data));
            }

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $leave,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_LEAVEAPPLICATION,
                $message
            );
            session()->flash('successmessage', $message);
            $res['success'] = $message;

            return $res;
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

        $array['from_date'] = date('d-m-Y', strtotime($leave->from_date));
        $array['to_date'] = date('d-m-Y', strtotime($leave->to_date));
        $array['reason_id'] = $leave->reason_id;
        $array['remarks'] = $leave->remarks;
        $array['leave_type_id'] = $leave->leave_type_id;
        $array['session'] = $leave->session;
        $array['leavelist'] = LeaveType::where([['school_id', $school_id], ['academic_year_id', $academic_year->id], ['status', 1]])->get();
        $array['reasonlist'] = AbsentReason::where('status', 1)->get();

        return $array;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function view($id)
    {
        //
        $leave = TeacherLeaveApplication::where('id', $id)->first();

        return view('/teacher/leave/show', ['leave' => $leave]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $leave = TeacherLeaveApplication::where('id', $id)->first();

        return view('/teacher/leave/edit', ['leave' => $leave]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(LeaveEditRequest $request, $id)
    {
        //
        try {
            $leave = TeacherLeaveApplication::where('id', $id)->first();

            $leave->from_date = date('Y-m-d H:i:s', strtotime($request->from_date));
            $leave->to_date = date('Y-m-d H:i:s', strtotime($request->to_date));
            $leave->reason_id = $request->reason_id;
            $leave->remarks = $request->remarks;
            $leave->leave_type_id = $request->leave_type_id;
            $leave->session = $request->session;
            $leave->status = 'pending';

            $leave->save();

            $this->LeaveApplyNotification($leave->user_id, 'update');

            $message = trans('messages.update_success_msg', ['module' => 'Leave Application']);

            $data = [];

            $user = User::where('id', Auth::user()->getTeacherDetails()['reporting_to'])->first();
            if ($user->id != Auth::id()) {
                $data['user'] = $user;
                $data['details'] = trans('notification.update_success_msg', ['user' => Auth::user()->FullName, 'module' => 'Leave Application']);

                event(new SingleNotificationEvent($data));
            }

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $leave,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_LEAVEAPPLICATION,
                $message
            );
            session()->flash('successmessage', $message);
            $res['success'] = $message;

            return $res;
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

            $data = [];

            $user = User::where('id', Auth::user()->getTeacherDetails()->reporting_to)->first();
            if ($user->id != Auth::id()) {
                $data['user'] = $user;
                $data['details'] = trans('notification.delete_success_msg', ['user' => Auth::user()->FullName, 'module' => 'Leave Application']);

                event(new SingleNotificationEvent($data));
            }

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $leave,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_LEAVEAPPLICATION,
                $message
            );

            session()->flash('successmessage', $message);
            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function approveList($id)
    {
        //
        $array = [];
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $leave = TeacherLeaveApplication::where('id', $id)->first();

        $array['teacher_name'] = $leave->teacher->FullName;
        $array['from_date'] = date('d-m-Y', strtotime($leave->from_date));
        $array['to_date'] = date('d-m-Y', strtotime($leave->to_date));
        $array['reason_id'] = $leave->absentReason->title;
        $array['remarks'] = $leave->remarks;
        $array['leave_type_id'] = $leave->leaveType->name;
        $array['session'] = ucfirst($leave->session);

        return $array;
    }

    public function approveCreate($id)
    {
        //
        $leave = TeacherLeaveApplication::where('id', $id)->first();

        return view('/teacher/leave/approvecreate', ['leave' => $leave]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
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

            $this->LeaveNotification($leave->user_id, $leave->status);

            /* $data = [];

             $user = User::where('id',$leave->user_id)->first();
             $data['user'] = $user;
             $data['details'] = trans('notification.approve_success_msg',['user' => Auth::user()->FullName , 'module' => 'Leave Application']);

             event(new SingleNotificationEvent($data));*/

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

        return view('/teacher/leave/rejectcreate', ['leave' => $leave]);
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

            $this->LeaveNotification($leave->user_id, $leave->status);

            /*$data = [];

            $user = User::where('id',$leave->user_id)->first();
            $data['user'] = $user;
            $data['details'] = trans('notification.reject_success_msg',['user' => Auth::user()->FullName , 'module' => 'Leave Application']);

            event(new SingleNotificationEvent($data));*/

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

    public function LeaveNotification($teacher_id, $status)
    {
        $teacher = User::find($teacher_id);

        $array = [];
        $array['school_id'] = Auth::user()->school_id;
        $array['user_id'] = $teacher->id;
        $array['message'] = trans('notification.leave_status_msg', ['status' => ucfirst($status)]);
        $array['type'] = 'leave';

        event(new SinglePushEvent($array));

        $data = [];

        $data['user'] = $teacher;
        $data['details'] = trans('notification.leave_status_msg', ['status' => ucfirst($status)]);

        event(new SingleNotificationEvent($data));
    }

    public function LeaveApplyNotification($teacher_id, $status)
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $teacherprofiles = TeacherProfile::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
            ['user_id', $teacher_id],
        ])->first();

        $student = User::find($teacher_id);
        $teacher = User::where('id', $teacherprofiles->reporting_to)->first();

        $array = [];

        $array['school_id'] = Auth::user()->school_id;
        $array['user_id'] = $teacher->id;
        $array['message'] = $student->FullName.'Applied leave';
        $array['type'] = 'leave';

        event(new SinglePushEvent($array));

        $data = [];

        $data['user'] = $teacher;

        if ($status == 'add') {
            $data['details'] = trans('notification.user_apply_leave', ['user' => $student->FullName]);
        } else {
            $data['details'] = trans('notification.user_update_leave',['user' => $student->FullName]);
        }

        event(new SingleNotificationEvent($data));
    }
}
