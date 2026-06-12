<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Teacher;

use App\Events\Notification\SingleNotificationEvent;
use App\Http\Requests\LessonPlanStep1Request;
use App\Http\Requests\LessonPlanStep2Request;
use App\Http\Requests\LessonPlanStep3Request;
use App\Http\Requests\LessonPlanStep4Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Models\LessonPlan;
use App\Traits\Common;
use Exception;

class LessonPlanEditController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editList($id)
    {
        //
        $lessonplan = LessonPlan::where('id',$id)->first();
        $parts = explode(':',$lessonplan->duration);

        $array = [];

        $array['standardLink_id']       =   $lessonplan->teacherlink->standardLink_id;
        $array['subject_id']            =   $lessonplan->teacherlink->subject_id;
        $array['unit_no']               =   $lessonplan->unit_no;
        $array['unit_name']             =   $lessonplan->unit_name;
        $array['description']           =   $lessonplan->description;
        $array['title']                 =   $lessonplan->title;
        $array['duration']              =   $parts[0]*60 + $parts[1];
        $array['objective']             =   $lessonplan->objective;
        $array['materials_required']    =   $lessonplan->materials_required;
        $array['introduction']          =   $lessonplan->introduction;
        $array['procedure']             =   $lessonplan->procedure;
        $array['conclusion']            =   $lessonplan->conclusion;
        $array['notes']                 =   $lessonplan->notes==null ? '':$lessonplan->notes;
        $array['assessment']            =   $lessonplan->assessment;
        $array['modification']          =   $lessonplan->modification;

        return $array;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $lessonplan = LessonPlan::where('id',$id)->first();

        return view('/teacher/lessonplan/edit', ['lessonplan' => $lessonplan]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function stepOne(LessonPlanStep1Request $request,$id)
    {
      //
        try
        {
            $lessonplan = LessonPlan::where('id',$id)->first();

            $lessonplan->unit_no            =   $request->unit_no;
            $lessonplan->unit_name          =   $request->unit_name;
            $lessonplan->title              =   $request->title;
            $lessonplan->duration           =   date('H:i:s', mktime(0,$request->duration,0));
            $lessonplan->description        =   $request->description;
            $lessonplan->status             =   'pending';

            $lessonplan->save();

            $message=trans('messages.save_success_msg',['module' => 'Step 1']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $lessonplan,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_LESSON_PLAN_1,
                $message
            );
            $res['message'] = $message;
            $res['id'] = $lessonplan->id;

            return $res;
        }
        catch(Exception $e)
        {
            //dd($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function stepTwo(LessonPlanStep2Request $request,$id)
    {
      //
        try
        {
            $lessonplan = LessonPlan::where('id',$id)->first();

            $lessonplan->objective          =   $request->objective;
            $lessonplan->materials_required =   $request->materials_required;
            $lessonplan->assessment         =   $request->assessment;
            $lessonplan->status             =   'pending';

            $lessonplan->save();

            $message=trans('messages.save_success_msg',['module' => 'Step 2']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $lessonplan,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_LESSON_PLAN_2,
                $message
            );
            $res['message'] = $message;
            $res['id'] = $lessonplan->id;

            return $res;
        }
        catch(Exception $e)
        {
            //dd($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function stepThree(LessonPlanStep3Request $request,$id)
    {
        //
        try
        {
            $lessonplan = LessonPlan::where('id',$id)->first();

            $lessonplan->introduction       =   $request->introduction;
            $lessonplan->procedure          =   $request->procedure;
            $lessonplan->conclusion         =   $request->conclusion;
            $lessonplan->status             =   'pending';

            $lessonplan->save();

            $data = [];

            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $principal = TeacherProfile::with('user')->where([['school_id',Auth::user()->school_id],['academic_year_id',$academic_year->id],['designation','principal']])->first();
            
            $data['user']       =   $principal->user;
            $data['details']    =   trans('notification.lesson_plan_update_success_msg');

            event(new SingleNotificationEvent($data));

            $message=trans('messages.save_success_msg',['module' => 'Step 3']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $lessonplan,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_LESSON_PLAN_3,
                $message
            );
            $res['message'] = $message;
            $res['id'] = $lessonplan->id;
            return $res;
        }
        catch(Exception $e)
        {
            //dd($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function stepFour(LessonPlanStep4Request $request,$id)
    {
        //
        try
        {
            $lessonplan = LessonPlan::where('id',$id)->first();

            $lessonplan->notes              =   $request->notes;
            $lessonplan->modification       =   $request->modification;
            $lessonplan->status             =   'pending';
            
            if(Auth::user()->hasRole('principal'))
            {
                $lessonplan->status = 'approved';
            }

            $lessonplan->save();

            $message=trans('messages.save_success_msg',['module' => 'Step 4']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $lessonplan,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_LESSON_PLAN_4,
                $message
            );
            $res['message'] = $message;
            $res['id'] = $lessonplan->id;
            return $res;
        }
        catch(Exception $e)
        {
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
    public function update(Request $request, $id)
    {
        //
        try
        {
            $message=trans('messages.update_success_msg',['module' => 'Lesson Plan']);

            return redirect()->back()->with('successmessage',$message);
        }
        catch(Exception $e)
        {
            //dd($e->getMessage());
        }
    }
}