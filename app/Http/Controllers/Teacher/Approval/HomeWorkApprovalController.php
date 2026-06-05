<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Teacher\Approval;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\HomeworkApproval;
use App\Models\User;
use App\Helpers\SiteHelper;
use App\Traits\EventProcess;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Events\Notification\SingleNotificationEvent;
use App\Events\Notification\ClassNotificationEvent;
use App\Events\StandardPushEvent;
use App\Events\SinglePushEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Exception;
use Log;

class HomeWorkApprovalController extends Controller
{
    use EventProcess;
    use LogActivity;
    use Common;

    public function approve(Request $request, $id)
    {
        \DB::beginTransaction();

        try {

            $homeworkapproval = HomeworkApproval::where('homework_id', $id)->firstOrFail();

            $homeworkapproval->comments      = $request->principal_comments;
            $homeworkapproval->approved_by   = Auth::id();
            $homeworkapproval->approved_at   = date('Y-m-d');
            $homeworkapproval->status        = 'approved';
            $homeworkapproval->save();

            $school_id      = Auth::user()->school_id;
            $academic_year  = SiteHelper::getAcademicYear($school_id);

            $homework = Homework::findOrFail($id);

            if (date('Y-m-d', strtotime($homework->date)) == date('Y-m-d')) {

                $data = [];
                $data['school_id']   = $school_id;
                $data['standard_id'] = $homework->standardLink_id;
                $data['message']     = 'New Homework Added';
                $data['type']        = 'homework';

                event(new StandardPushEvent($data));

                $array = [];
                $array['school_id']       = $school_id;
                $array['standardLink_id'] = $homework->standardLink_id;
                $array['details']         = 'New Homework Added';

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
                                date('Y-m-d'),
                                $homework->subject->name ?? '',
                                $homework->title ?? '',
                                $parent->userParent->id,
                                $parent->userParent->email,
                                $parent->userParent->mobile_no
                            );
                        }
                    }
                }
            }

            $message = trans('messages.approve_success_msg', [
                'module' => 'Homework'
            ]);

            $ip = $this->getRequestIP();

            $this->doActivityLog(
                $homeworkapproval,
                Auth::user(),
                [
                    'ip'      => $ip,
                    'details' => $_SERVER['HTTP_USER_AGENT']
                ],
                defined('LOGNAME_APPROVE_HOMEWORK')
                    ? LOGNAME_APPROVE_HOMEWORK
                    : 'APPROVE_HOMEWORK',
                $message
            );

            \DB::commit();

            return [
                'success' => $message
            ];

        } catch (Exception $e) {

            \DB::rollBack();

            Log::error($e->getMessage());

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        \DB::beginTransaction();

        try {

            $homeworkapproval = HomeworkApproval::where('homework_id', $id)->firstOrFail();

            $homeworkapproval->comments      = $request->principal_comments;
            $homeworkapproval->approved_by   = Auth::id();
            $homeworkapproval->approved_at   = date('Y-m-d');
            $homeworkapproval->status        = 'rejected';

            $homeworkapproval->save();

            $school_id = Auth::user()->school_id;

            $teacher = User::findOrFail(
                $homeworkapproval->homework->teacher_id
            );

            $message = trans('messages.reject_success_msg', [
                'module' => 'Homework'
            ]);

            $data = [];

            $data['school_id'] = $school_id;
            $data['user_id']   = $teacher->id;
            $data['message']   = 'Homework Rejected';
            $data['type']      = 'homework';

            event(new SinglePushEvent($data));

            $array = [];

            $array['user']    = $teacher;
            $array['details'] = trans(
                'notification.reject_success_msg',
                [
                    'user'   => Auth::user()->FullName,
                    'module' => 'Homework'
                ]
            );

            event(new SingleNotificationEvent($array));

            $ip = $this->getRequestIP();

            $this->doActivityLog(
                $homeworkapproval,
                Auth::user(),
                [
                    'ip'      => $ip,
                    'details' => $_SERVER['HTTP_USER_AGENT']
                ],
                defined('LOGNAME_REJECT_HOMEWORK')
                    ? LOGNAME_REJECT_HOMEWORK
                    : 'REJECT_HOMEWORK',
                $message
            );

            \DB::commit();

            return [
                'success' => $message
            ];

        } catch (Exception $e) {

            \DB::rollBack();

            Log::error($e->getMessage());

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}