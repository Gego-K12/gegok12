<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\LessonPlan;
use App\Models\LessonPlanApproval;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LessonPlanApprovalController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function approve(Request $request, $id)
    {
        //
        \DB::beginTransaction();
        try {
            $lessonplan = LessonPlan::where('id', $id)->first();

            $lessonplan->status = 'approved';

            $lessonplan->save();

            $lessonplanapproval = new LessonPlanApproval;

            $lessonplanapproval->lesson_plan_id = $lessonplan->id;
            $lessonplanapproval->comments = $request->comments;
            $lessonplanapproval->approved_by = Auth::id();
            $lessonplanapproval->approved_at = date('Y-m-d');

            $lessonplanapproval->save();

            $message = trans('messages.approve_success_msg', ['module' => 'Lesson Plan']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $lessonplanapproval,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_APPROVE_LESSON_PLAN,
                $message
            );
            $res['success'] = $message;

            \DB::commit();

            return $res;
        } catch (Exception $e) {
            \DB::rollBack();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function reject(Request $request, $id)
    {
        //
        \DB::beginTransaction();
        try {
            $lessonplan = LessonPlan::where('id', $id)->first();

            $lessonplan->status = 'rejected';

            $lessonplan->save();

            $lessonplanapproval = new LessonPlanApproval;

            $lessonplanapproval->lesson_plan_id = $lessonplan->id;
            $lessonplanapproval->comments = $request->comments;
            $lessonplanapproval->approved_by = Auth::id();
            $lessonplanapproval->approved_at = date('Y-m-d');

            $lessonplanapproval->save();

            $message = trans('messages.reject_success_msg', ['module' => 'Lesson Plan']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $lessonplanapproval,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_REJECT_LESSON_PLAN,
                $message
            );
            $res['success'] = $message;

            \DB::commit();

            return $res;
        } catch (Exception $e) {
            \DB::rollBack();
        }
    }
}
