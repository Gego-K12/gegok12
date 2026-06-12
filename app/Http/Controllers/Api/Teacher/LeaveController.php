<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Api\Teacher;

use App\Http\Resources\API\Teacher\Leave as LeaveResource;
use App\Events\Notification\SingleNotificationEvent;
use App\Http\Requests\API\Teacher\LeaveEditRequest;
use App\Http\Requests\API\Teacher\LeaveAddRequest;
use App\Http\Requests\LeaveApproveRequest;
use App\Models\TeacherLeaveApplication;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Events\SinglePushEvent;
use App\Models\TeacherProfile;
use App\Models\AbsentReason;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Models\LeaveType;
use App\Models\User;
use App\Traits\Common;
use Exception;
use Log;

class LeaveController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $application = TeacherLeaveApplication::where([
                        ['user_id',Auth::id()],
                        ['school_id',$school_id],
                        ['academic_year_id',$academic_year->id]
                    ])->latest()->get(); 

        $application = LeaveResource::collection($application);

        return response()->json([
            'success'   =>  true,
            'message'   =>  'Leave List',
            'data'      =>  $application
        ],200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function availableList()
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $leavetypes       = LeaveType::where([['school_id',$school_id],['academic_year_id',$academic_year->id],['status',1]])->orWhereHas('teacherLeaveApplication',function ($query){
            $query->where('user_id',Auth::id());
        })->get();

        $applications = TeacherLeaveApplication::where([
                        ['user_id',Auth::id()],
                        ['school_id',$school_id],
                        ['academic_year_id',$academic_year->id]
                    ])->get();
        foreach ($leavetypes as $leavetype) 
        {
           foreach ($applications as $application) 
            {
                if($leavetype->id == $application->leave_type_id)
                {
                    $array['name'][] = $leavetype->name;
                    $array['totalDays'][] = $leavetype->max_no_of_days;
                    $array['leaveUsed'][] = $application->teacher->LeaveCount;
                    $array['leaveAvailable'][]= $application->leaveType->max_no_of_days - $application->teacher->LeaveCount;
                }
                else
                {
                    $array['name'][]      = $leavetype->name;
                    $array['totalDays'][] = $leavetype->max_no_of_days;
                    $array['leaveUsed'][] = 0;
                    $array['leaveAvailable'][]= $leavetype->max_no_of_days;
                }
            }
        }

        return response()->json([
            'success'   =>  true,
            'message'   =>  'Available List',
            'data'      =>  $array
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $array['leavelist']       = LeaveType::where([['school_id',$school_id],['academic_year_id',$academic_year->id],['status',1]])->get();
        $array['reasonlist']  = AbsentReason::where('status',1)->get();
        $type=array(['id' =>'day','name' =>'FullDay'],['id' =>'forenoon','name' =>'Forenoon'],['id' =>'afternoon','name' =>'Afternoon']);
        $array['session']=$type;
       
        return $array;


        return response()->json([
            'success'   =>  true,
            'message'   =>  'Leave Type List & Reason List',
            'data'      =>  $array
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeaveAddRequest $request)
    {
        //
        try
        {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $leave                      = new TeacherLeaveApplication;

            $leave->school_id           = $school_id;
            $leave->academic_year_id    = $academic_year->id;
            $leave->user_id             = Auth::id();
            $leave->from_date           = date('Y-m-d H:i:s',strtotime($request->from_date));
            $leave->to_date             = date('Y-m-d H:i:s',strtotime($request->to_date));
            $leave->reason_id           = $request->reason_id;
            $leave->remarks             = $request->remarks;
            $leave->leave_type_id       = $request->leave_type_id;
            $leave->session             = $request->session;
            $leave->status              = "pending";

            $leave->save();

            $this->LeaveApplyNotification($leave->user_id,'add');

            $message=trans('messages.add_success_msg',['module' => 'Leave Application']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $leave,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_LEAVEAPPLICATION,
                $message
            );

            return response()->json([
                'success'   =>  true,
                'message'   =>  $message,
            ],200);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $array = [];

        $leave = TeacherLeaveApplication::where('id',$id)->first();

        $array['from_date']     =   date('d-m-Y H:i:s',strtotime($leave->from_date));
        $array['to_date']       =   date('d-m-Y H:i:s',strtotime($leave->to_date ));
        $array['reason_id']     =   $leave->reason_id;
        $array['remarks']       =   $leave->remarks;
        $array['leave_type_id'] =   $leave->leave_type_id;
        $array['session']       =   $leave->session;

        return response()->json([
            'success'   =>  true,
            'message'   =>  'Show Leave',
            'data'      =>  $array
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LeaveEditRequest $request, $id)
    {
        //
        try
        {
            $leave = TeacherLeaveApplication::where('id',$id)->first();

            if($leave->status == 'pending')
            {
                $leave->from_date           = date('Y-m-d H:i:s',strtotime($request->from_date));
                $leave->to_date             = date('Y-m-d H:i:s',strtotime($request->to_date));
                $leave->reason_id           = $request->reason_id;
                $leave->remarks             = $request->remarks;
                $leave->leave_type_id       = $request->leave_type_id;
                $leave->session             = $request->session;
                $leave->status              = "pending";

                $leave->save();

                $this->LeaveApplyNotification($leave->user_id,'update');

                $message=trans('messages.update_success_msg',['module' => 'Leave Application']);

                $ip= $this->getRequestIP();
                $this->doActivityLog(
                    $leave,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                    LOGNAME_EDIT_LEAVEAPPLICATION,
                    $message
                );
            }
            else
            {
                $message = trans('messages.approval_done_msg',['module' => 'Leave Application']);
            }

            return response()->json([
                'success'   =>  true,
                'message'   =>  $message,
            ],200);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try
        {
            $leave = TeacherLeaveApplication::where('id',$id)->first();
            if($leave->status == 'pending')
            {
                $leave->status     =   'cancelled';
                $leave->save();

                $leave->delete();

                $message=trans('messages.delete_success_msg',['module' => 'Leave Application']);


                $ip= $this->getRequestIP();
                $this->doActivityLog(
                    $leave,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                    LOGNAME_DELETE_LEAVEAPPLICATION,
                    $message
                );
            }
            else
            {
                $message = trans('messages.delete_fail_approval_done_msg',['module' => 'Leave Application']);
            }

            return response()->json([
                'success'   =>  true,
                'message'   =>  $message,
            ],200);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function leaveCheckList()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        if(Auth::user()->hasRole('leave_checker'))
        {
            $teacherprofiles = TeacherProfile::where([
                    ['school_id',$school_id],
                    ['academic_year_id',$academic_year->id],
                    ['reporting_to',Auth::id()]
                ])->pluck('user_id')->toArray();

            $application = TeacherLeaveApplication::where([
                        ['school_id',$school_id],
                        ['academic_year_id',$academic_year->id],
                        ['status','pending']
                    ])->whereIn('user_id',$teacherprofiles)->get();
        }

        $application = LeaveResource::collection($application);

        return response()->json([
            'success'   =>  true,
            'message'   =>  'Leave List',
            'data'      =>  $application
        ],200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function leaveCheckListApproved()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        if(Auth::user()->hasRole('leave_checker'))
        {
            $teacherprofiles = TeacherProfile::where([
                    ['school_id',$school_id],
                    ['academic_year_id',$academic_year->id],
                    ['reporting_to',Auth::id()]
                ])->pluck('user_id')->toArray();

            $application = TeacherLeaveApplication::where([
                        ['school_id',$school_id],
                        ['academic_year_id',$academic_year->id],
                        ['status','approved']
                    ])->whereIn('user_id',$teacherprofiles)->get();
        }

        $application = LeaveResource::collection($application);

        return response()->json([
            'success'   =>  true,
            'message'   =>  'Approved Leave List',
            'data'      =>  $application
        ],200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function leaveCheckListRejected()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        if(Auth::user()->hasRole('leave_checker'))
        {
            $teacherprofiles = TeacherProfile::where([
                    ['school_id',$school_id],
                    ['academic_year_id',$academic_year->id],
                    ['reporting_to',Auth::id()]
                ])->pluck('user_id')->toArray();

            $application = TeacherLeaveApplication::where([
                        ['school_id',$school_id],
                        ['academic_year_id',$academic_year->id],
                        ['status','cancelled']
                    ])->whereIn('user_id',$teacherprofiles)->get();
        }

        $application = LeaveResource::collection($application);

        return response()->json([
            'success'   =>  true,
            'message'   =>  'Rejected Leave List',
            'data'      =>  $application
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approveStore(LeaveApproveRequest $request, $id)
    {
        //
        try
        {
            $leave = TeacherLeaveApplication::where('id',$id)->first();

            $leave->comments    =   $request->comments;
            $leave->approved_by =   Auth::id();
            $leave->approved_on =   date('Y-m-d');
            $leave->status      =   "approved";

            $leave->save();

            $this->LeaveNotification($leave->user_id,$leave->status);

            $message=trans('messages.approve_success_msg',['module' => 'Leave Application']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $leave,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_APPROVE_LEAVEAPPLICATION,
                $message
            );

            return response()->json([
                'success'   =>  true,
                'message'   =>  $message,
            ],200);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rejectStore(LeaveApproveRequest $request, $id)
    {
        //
        try
        {
            $leave = TeacherLeaveApplication::where('id',$id)->first();

            $leave->comments    =   $request->comments;
            $leave->approved_by =   Auth::id();
            $leave->approved_on =   date('Y-m-d');
            $leave->status      =   "cancelled";

            $leave->save();

            $this->LeaveNotification($leave->user_id,$leave->status);

            $message=trans('messages.reject_success_msg',['module' => 'Leave Application']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $leave,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_REJECT_LEAVEAPPLICATION,
                $message
            );

            return response()->json([
                'success'   =>  true,
                'message'   =>  $message,
            ],200);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    public function LeaveNotification($teacher_id,$status)
    {
        $teacher = User::find($teacher_id);
        
        $array=[];
        $array['school_id']  =   Auth::user()->school_id;
        $array['user_id']    =   $teacher->id;
        $array['message']    =   trans('notification.leave_status_msg',['status' => ucfirst($status)]);
        $array['type']       =   'leave';

        event(new SinglePushEvent($array));
    


        $data = [];

        $data['user']       =   $teacher;
        $data['details']    =   trans('notification.leave_status_msg',['status' => ucfirst($status)]);

        event(new SingleNotificationEvent($data));                                        
    }

    public function LeaveApplyNotification($teacher_id,$status)
    {
         $school_id = Auth::user()->school_id;
         $academic_year = SiteHelper::getAcademicYear($school_id);
         $teacherprofiles = TeacherProfile::where([
                    ['school_id',$school_id],
                    ['academic_year_id',$academic_year->id],
                    ['user_id',$teacher_id]
                ])->first();

        $student = User::find($teacher_id);       
        $teacher = User::where('id',$teacherprofiles->reporting_to)->first();
                    
        $array=[];

        $array['school_id']  =   Auth::user()->school_id;
        $array['user_id']    =   $teacher->id;
        $array['message']    =   $student->FullName.'Applied leave';
        $array['type']       =   'leave';

        event(new SinglePushEvent($array));

        $data = [];

        $data['user']       =   $teacher;

        if($status=='add'){
        $data['details']    =   trans('notification.user_apply_leave',['user' => $student->FullName]);
        }
        else{
        $data['details']    =   trans('notification.user_update_leave',['user' => $student->FullName]);
        }

        event(new SingleNotificationEvent($data));
    }
}