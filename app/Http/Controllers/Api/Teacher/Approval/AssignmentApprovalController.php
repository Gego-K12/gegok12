<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Api\Teacher\Approval;

use App\Events\Notification\ClassNotificationEvent;
use App\Http\Requests\AssignmentApprovalRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AssignmentApproval;
use App\Events\StandardPushEvent;
use Illuminate\Http\Request;
use App\Traits\EventProcess;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Models\Assignment;
use App\Traits\Common;
use Exception;
use Log;

class AssignmentApprovalController extends Controller
{
    use EventProcess;
    use LogActivity;
    use Common;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function approve(AssignmentApprovalRequest $request,$id)
    {
        //
        \DB::beginTransaction();
        try
        {
            $assignmentapproval = AssignmentApproval::where('assignment_id',$id)->first();

            $assignmentapproval->comments           =   $request->principal_comments;
            $assignmentapproval->approved_by        =   Auth::id();
            $assignmentapproval->approved_at        =   date('Y-m-d');
            $assignmentapproval->status             =   'approved';

            $assignmentapproval->save();

            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $assignment = Assignment::where('id',$id)->first();

            $data=[];

            $data['school_id']      =   $school_id;
            $data['standard_id']    =   $assignment->standardLink_id;
            $data['message']        =   'New Assignment Added';
            $data['type']           =   'assignment';

            event(new StandardPushEvent($data));

            $array = [];

            $array['school_id']         = $school_id;
            $array['standardLink_id']   = $assignment->standardLink_id;
            $array['details']           = trans('notification.teacher_assignment_add_msg');  

            event(new ClassNotificationEvent($array));         

            $studentAcademics = SiteHelper::getClassStudents($school_id,$academic_year->id,$assignment->standardLink_id);
            foreach($studentAcademics as $studentAcademic)
            {
                foreach ($studentAcademic->user->parents as $parent) 
                {
                    $this->sendToAssignmentReminder($school_id,date('Y-m-d',strtotime($request->submission_date)),$assignment->subject->name,$assignment->title,$parent->userParent->id,$parent->userParent->email,$parent->userParent->mobile_no);
                }  
            }

            $message=trans('messages.approve_success_msg',['module' => 'Assignment']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $assignmentapproval,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_APPROVE_ASSIGNMENT,
                $message
            );

            \DB::commit();
            return response()->json([
                'success'   =>  true,
                'message'   =>  $message,
            ],200);
        }
        catch(Exception $e)
        {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject(AssignmentApprovalRequest $request, $id)
    {
        //
        \DB::beginTransaction();
        try
        {
            $assignmentapproval = AssignmentApproval::where('assignment_id',$id)->first();

            $assignmentapproval->comments           =   $request->principal_comments;
            $assignmentapproval->approved_by        =   Auth::id();
            $assignmentapproval->approved_at        =   date('Y-m-d');
            $assignmentapproval->status             =   'rejected';

            $assignmentapproval->save();

            $message=trans('messages.reject_success_msg',['module' => 'Assignment']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $assignmentapproval,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_REJECT_ASSIGNMENT,
                $message
            );

            \DB::commit();
            return response()->json([
                'success'   =>  true,
                'message'   =>  $message,
            ],200);
        }
        catch(Exception $e)
        {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }
}