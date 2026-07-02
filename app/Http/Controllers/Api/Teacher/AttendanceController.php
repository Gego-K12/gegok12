<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Api\Teacher;

use App\Http\Resources\API\Teacher\Studentlist as StudentlistResource;
use App\Http\Resources\API\Teacher\AbsentList as AbsentListResource;
use App\Events\Notification\SingleNotificationEvent;
use App\Http\Requests\API\Teacher\AttendanceAddRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Events\SinglePushEvent;
use App\Models\StudentAcademic;
use Illuminate\Http\Request;
use App\Models\StandardLink;
use App\Models\AbsentReason;
use App\Traits\EventProcess;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Models\Attendance;
use League\Csv\Writer;
use App\Traits\Common;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Log;

class AttendanceController extends Controller
{
    use EventProcess;
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
        try
        {
            $array = [];
            $school_id = Auth::user()->school_id;

            $academic_year = SiteHelper::getAcademicYear($school_id);

            $standardLink = StandardLink::where([
                    ['school_id',$school_id],
                    ['academic_year_id',$academic_year->id],
                    ['class_teacher_id',Auth::id()]
                ])->first();

            $studentAcademic = StudentAcademic::with('user')->where([
                    ['school_id',$school_id],
                    ['academic_year_id',$academic_year->id],
                    ['standardLink_id',$standardLink->id]
                ])->whereHas('user', function($q){
                        $q->where([['status','active'],['deleted_at',null]]);
                    })->get()->sortBy('user.userprofile.firstname');

            $array['standardlist']      = SiteHelper::getStandardLinkList($school_id);
            $array['studentlist']       = StudentlistResource::collection($studentAcademic);
            $array['absentReasonlist']  = AbsentReason::where('status',1)->get();

            return $array;
        }
        catch(Exception $e)
        {
            //Log::info($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttendanceAddRequest $request)
    {
        //
        try
        {
            $school_id      = Auth::user()->school_id;
            $academic_year  = SiteHelper::getAcademicYear($school_id);
            $absent_id = [];

            foreach ($request->AbsentList as $list) 
            {
                $student = User::where('id',$list['student_id'])->first();
                $absent_id[] = $student->id;

                $attendance = new Attendance;

                $attendance->school_id          = $school_id;
                $attendance->academic_year_id   = $academic_year->id;
                $attendance->standardLink_id    = $request->Standardlinkid;
                $attendance->date               = date('Y-m-d',strtotime($request->Date));
                $attendance->session            = $request->Session;
                $attendance->user_id            = $list['student_id'];
                $attendance->reason_id          = $list['reasonId'];
                $attendance->remarks            = $list['remarks'];
                $attendance->status             = 0;
                $attendance->recorded_by        = Auth::id();

                $attendance->save();

                foreach ($student->parents as $parent) 
                {
                    $array=[];

                    $array['school_id']  = $school_id;
                    $array['user_id']    = $parent->userParent->id;
                    $array['message']    = 'Dear Parent, Kindly make a note that your child '.$student->FullName.' is absent for school today('.ucfirst($request->Session).').';
                    $array['type']       = 'private message';
                            
                    event(new SinglePushEvent($array));

                    $this->sendToAttendanceReminder($school_id,$attendance->date,$parent->userParent->id,$parent->userParent->mobile_no,$parent->userParent->email,$student->FullName);
                } 

                    $datas = [];
                    //$child = User::where('id',$student->id)->first();
                    $datas['user']       =   $student;
                    $datas['type']       =   'attendance';
                    $datas['details']    =   'Dear Parent, Kindly make a note that your child '.$student->FullName.' is absent for school today('.ucfirst($request->Session).').';
                    event(new SingleNotificationEvent($datas)); 
            }

            $presents = StudentAcademic::with('user')->where([
                ['school_id',$school_id],
                ['academic_year_id',$academic_year->id],
                ['standardLink_id',$request->Standardlinkid]
            ])->whereNotIn('user_id',$absent_id)->pluck('user_id')->toArray();

            foreach ($presents as $user_id) 
            {
                $attendance = new Attendance;

                $attendance->school_id          = $school_id;
                $attendance->academic_year_id   = $academic_year->id;
                $attendance->standardLink_id    = $request->Standardlinkid;
                $attendance->date               = date('Y-m-d',strtotime($request->Date));
                $attendance->session            = $request->Session;
                $attendance->user_id            = $user_id;
                $attendance->status             = 1;
                $attendance->recorded_by        = Auth::id();

                $attendance->save();
            }

            $message = trans('messages.add_success_msg',['module' => 'Attendance']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $attendance,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_ATTENDANCE,
                $message
            );

            \Session::put('list',$request->AbsentList);

            //$this->absentList($request->AbsentList);

            return response()->json([
                'success'   =>  true,
                'message'   =>  trans('messages.add_success_msg',['module' => 'Attendance']),
            ],200);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function absentList($standardLink_id,$date,$session)
    {
        //
        $date = date('Y-m-d',strtotime($date));
        $school_id      = Auth::user()->school_id;
        $academic_year  = SiteHelper::getAcademicYear($school_id);
        $attendances = Attendance::where([
            ['school_id',$school_id],
            ['academic_year_id',$academic_year->id],
            ['standardLink_id',$standardLink_id],
            ['date',$date],
            ['session',$session],
            ['status',0]
        ])->get();

        $total=count($attendances);
        $attendances = AbsentListResource::collection($attendances);

        return response()->json([
                'success'   =>  true,
                'message'   =>  'Absent List',
                'data'      =>  $attendances,
                'count'     =>  $total
        ],200);
    }
}