<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\LessonPlanApproval;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Models\LessonPlan;
use App\Traits\Common;
use Exception;

class LessonPlanApprovalController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request,$id)
    {
        //
        \DB::beginTransaction();
        try
        {
            $lessonplan             = LessonPlan::where('id',$id)->first();

            $lessonplan->status     = 'approved';

            $lessonplan->save();

            $lessonplanapproval = new LessonPlanApproval;

            $lessonplanapproval->lesson_plan_id     =   $lessonplan->id;
            $lessonplanapproval->comments           =   $request->comments;
            $lessonplanapproval->approved_by        =   Auth::id();
            $lessonplanapproval->approved_at        =   date('Y-m-d');

            $lessonplanapproval->save();

            $message=trans('messages.approve_success_msg',['module' => 'Lesson Plan']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $lessonplanapproval,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_APPROVE_LESSON_PLAN,
                $message
            );
            $res['success'] = $message;

            \DB::commit();
            return $res;
        }
        catch(Exception $e)
        {
            \DB::rollBack();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, $id)
    {
        //
        \DB::beginTransaction();
        try
        {
            $lessonplan             = LessonPlan::where('id',$id)->first();

            $lessonplan->status     = 'rejected';

            $lessonplan->save();

            $lessonplanapproval = new LessonPlanApproval;

            $lessonplanapproval->lesson_plan_id     =   $lessonplan->id;
            $lessonplanapproval->comments           =   $request->comments;
            $lessonplanapproval->approved_by        =   Auth::id();
            $lessonplanapproval->approved_at        =   date('Y-m-d');

            $lessonplanapproval->save();

            $message=trans('messages.reject_success_msg',['module' => 'Lesson Plan']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $lessonplanapproval,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_REJECT_LESSON_PLAN,
                $message
            );
            $res['success'] = $message;

            \DB::commit();
            return $res;
        }
        catch(Exception $e)
        {
            \DB::rollBack();
        }
    }
}
