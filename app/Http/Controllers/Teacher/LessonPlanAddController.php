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
use App\Http\Requests\PublishLessonPlanRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Models\Teacherlink;
use App\Models\LessonPlan;
use App\Traits\Common;
use Exception;

class LessonPlanAddController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addList()
    {
        //
        $array = SiteHelper::getStandardSubjectList(Auth::user()->school_id,Auth::id());
        
        return $array;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('/teacher/lessonplan/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function stepOne(LessonPlanStep1Request $request)
    {
      //
        try
        {
            $lessonplan = new LessonPlan;
            $teacherLink = Teacherlink::where([
                ['standardLink_id',$request->standardLink_id],
                ['subject_id',$request->subject_id],
                ['teacher_id',Auth::id()]
            ])->first();

            $lessonplan->teacher_link_id    =   $teacherLink->id;
            $lessonplan->unit_no            =   $request->unit_no;
            $lessonplan->unit_name          =   $request->unit_name;
            $lessonplan->title              =   $request->title;
            $lessonplan->duration           =   date('H:i:s', mktime(0,$request->duration,0));
            $lessonplan->description        =   $request->description;
            $lessonplan->status             =   'draft';

            $lessonplan->save();

            $message=trans('messages.save_success_msg',['module' => 'Step 1']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $lessonplan,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_LESSON_PLAN_1,
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
            $lessonplan->status             =   'draft';

            $lessonplan->save();

            $message=trans('messages.save_success_msg',['module' => 'Step 2']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $lessonplan,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_LESSON_PLAN_2,
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
            $data['details']    =   trans('notification.lesson_plan_add_success_msg');

            event(new SingleNotificationEvent($data));
            
            $message=trans('messages.save_success_msg',['module' => 'Step 3']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $lessonplan,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_LESSON_PLAN_3,
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
                LOGNAME_ADD_LESSON_PLAN_4,
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
    public function store(Request $request)
    {
        //
        try
        {
            $message=trans('messages.add_success_msg',['module' => 'Lesson Plan']);

            return redirect()->back()->with('successmessage',$message);
        }
        catch(Exception $e)
        {
            //dd($e->getMessage());
        }
    }
    public function publish(PublishLessonPlanRequest $request,$id)
    {
        try{
            $lessonplan = LessonPlan::find($id);

            if (!$lessonplan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lesson plan not found',
                ], 404);
            }

            if ($lessonplan->status != 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only approved lesson plan can be published',
                ], 422);
            }

            $lessonplan->is_published = 1;
            $lessonplan->published_at = now();
            $lessonplan->start_date = date('Y-m-d', strtotime($request->start_date));
            $lessonplan->end_date = date('Y-m-d', strtotime($request->end_date));

            $lessonplan->save();

            return response()->json([
                'success' => true,
                'message' => 'Lesson plan published successfully',
                'data' => [
                    'id' => $lessonplan->id,
                ],
            ]);

        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }
}