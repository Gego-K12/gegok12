<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Api\Teacher\Approval;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignmentApprovalRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\HomeworkApproval;
use App\Models\Homework;
use App\Events\Notification\ClassNotificationEvent;
use App\Events\StandardPushEvent;
use App\Traits\EventProcess;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Helpers\SiteHelper;
use Exception;
use Log;

class HomeworkApprovalController extends Controller
{
    use EventProcess;
    use LogActivity;
    use Common;

    public function approve(AssignmentApprovalRequest $request, $id)
    {
        \DB::beginTransaction();

        try {

            $homeworkApproval = HomeworkApproval::where('homework_id', $id)->firstOrFail();

            $homeworkApproval->comments     = $request->principal_comments;
            $homeworkApproval->approved_by  = Auth::id();
            $homeworkApproval->approved_at  = now()->format('Y-m-d');
            $homeworkApproval->status       = 'approved';
            $homeworkApproval->save();

            $homework = Homework::findOrFail($id);

            // publish homework
            $homework->status = 'publish';
            $homework->save();

            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $data = [
                'school_id'   => $school_id,
                'standard_id' => $homework->standardLink_id,
                'message'     => 'New Homework Added',
                'type'        => 'homework'
            ];

            event(new StandardPushEvent($data));

            $array = [
                'school_id'       => $school_id,
                'standardLink_id' => $homework->standardLink_id,
                'details'         => 'New Homework Added'
            ];

            event(new ClassNotificationEvent($array));

            $studentAcademics = SiteHelper::getClassStudents(
                $school_id,
                $academic_year->id,
                $homework->standardLink_id
            );

            foreach ($studentAcademics as $studentAcademic) {
                foreach ($studentAcademic->user->parents as $parent) {

                    if (method_exists($this, 'sendToHomeworkReminder')) {

                        $this->sendToHomeworkReminder(
                            $school_id,
                            $homework->submission_date,
                            optional($homework->subject)->name,
                            'Homework',
                            $parent->userParent->id,
                            $parent->userParent->email,
                            $parent->userParent->mobile_no
                        );
                    }
                }
            }

            $message = trans(
                'messages.approve_success_msg',
                ['module' => 'Homework']
            );

            $this->doActivityLog(
                $homeworkApproval,
                Auth::user(),
                [
                    'ip'      => $this->getRequestIP(),
                    'details' => request()->userAgent()
                ],
                defined('LOGNAME_APPROVE_HOMEWORK')
                    ? LOGNAME_APPROVE_HOMEWORK
                    : 'approve_homework',
                $message
            );

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
            ], 200);

        } catch (Exception $e) {

            \DB::rollBack();

            Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function reject(AssignmentApprovalRequest $request, $id)
    {
        \DB::beginTransaction();

        try {

            $homeworkApproval = HomeworkApproval::where('homework_id', $id)->firstOrFail();

            $homeworkApproval->comments     = $request->principal_comments;
            $homeworkApproval->approved_by  = Auth::id();
            $homeworkApproval->approved_at  = now()->format('Y-m-d');
            $homeworkApproval->status       = 'rejected';
            $homeworkApproval->save();

            $message = trans(
                'messages.reject_success_msg',
                ['module' => 'Homework']
            );

            $this->doActivityLog(
                $homeworkApproval,
                Auth::user(),
                [
                    'ip'      => $this->getRequestIP(),
                    'details' => request()->userAgent()
                ],
                defined('LOGNAME_REJECT_HOMEWORK')
                    ? LOGNAME_REJECT_HOMEWORK
                    : 'reject_homework',
                $message
            );

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
            ], 200);

        } catch (Exception $e) {

            \DB::rollBack();

            Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}