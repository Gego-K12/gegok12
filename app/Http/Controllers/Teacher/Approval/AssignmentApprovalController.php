<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Teacher\Approval;

use App\Events\Notification\ClassNotificationEvent;
use App\Events\Notification\SingleNotificationEvent;
use App\Events\SinglePushEvent;
use App\Events\StandardPushEvent;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssignmentApprovalRequest;
use App\Models\Assignment;
use App\Models\AssignmentApproval;
use App\Models\User;
use App\Traits\Common;
use App\Traits\EventProcess;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Log;

class AssignmentApprovalController extends Controller
{
    use Common;
    use EventProcess;
    use LogActivity;

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function approve(AssignmentApprovalRequest $request, $id)
    {
        //
        \DB::beginTransaction();
        try {
            $assignmentapproval = AssignmentApproval::where('assignment_id', $id)->first();

            $assignmentapproval->comments = $request->principal_comments;
            $assignmentapproval->approved_by = Auth::id();
            $assignmentapproval->approved_at = date('Y-m-d');
            $assignmentapproval->status = 'approved';

            $assignmentapproval->save();

            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $assignment = Assignment::where('id', $id)->first();

            if (date('Y-m-d', strtotime($assignment->assigned_date)) == date('Y-m-d')) {
                $data = [];

                $data['school_id'] = $school_id;
                $data['standard_id'] = $assignment->standardLink_id;
                $data['message'] = 'New Assignment Added';
                $data['type'] = 'assignment';

                event(new StandardPushEvent($data));

                $array = [];

                $array['school_id'] = $school_id;
                $array['standardLink_id'] = $assignment->standardLink_id;
                $array['details'] = trans('notification.teacher_assignment_add_msg');

                event(new ClassNotificationEvent($array));

                $studentAcademics = SiteHelper::getClassStudents($school_id, $academic_year->id, $assignment->standardLink_id);
                foreach ($studentAcademics as $studentAcademic) {
                    foreach ($studentAcademic->user->parents as $parent) {
                        $this->sendToAssignmentReminder($school_id, date('Y-m-d', strtotime($request->submission_date)), $assignment->subject->name, $assignment->title, $parent->userParent->id, $parent->userParent->email, $parent->userParent->mobile_no);
                    }
                }
            }

            $message = trans('messages.approve_success_msg', ['module' => 'Assignment']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $assignmentapproval,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_APPROVE_ASSIGNMENT,
                $message
            );
            $res['success'] = $message;

            \DB::commit();

            return $res;
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function reject(AssignmentApprovalRequest $request, $id)
    {
        //

        \DB::beginTransaction();
        try {
            $assignmentapproval = AssignmentApproval::where('assignment_id', $id)->first();

            $assignmentapproval->comments = $request->principal_comments;
            $assignmentapproval->approved_by = Auth::id();
            $assignmentapproval->approved_at = date('Y-m-d');
            $assignmentapproval->status = 'rejected';

            $assignmentapproval->save();

            $teacher = User::where('id', $assignmentapproval->assignment->teacher_id)->first();

            $message = trans('messages.reject_success_msg', ['module' => 'Assignment']);

            $data = [];

            $data['school_id'] = $school_id;
            $data['user_id'] = $teacher->id;
            $data['message'] = 'Assignment Rejected';
            $data['type'] = 'assignment';

            event(new SinglePushEvent($data));

            $array = [];

            $array['user'] = $teacher;
            $array['details'] = trans('notification.reject_success_msg', ['user' => Auth::user()->FullName, 'module' => 'Assignment']);

            event(new SingleNotificationEvent($array));

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $assignmentapproval,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_REJECT_ASSIGNMENT,
                $message
            );
            $res['success'] = $message;

            \DB::commit();

            return $res;
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }
}
