<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Api\Teacher;

use App\Http\Resources\API\Teacher\LessonPlan as LessonPlanResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Events\Notification\SingleNotificationEvent;
use App\Http\Requests\PublishLessonPlanRequest;
use App\Http\Requests\LessonPlanStep1Request;
use App\Http\Requests\LessonPlanStep2Request;
use App\Http\Requests\LessonPlanStep3Request;
use App\Http\Requests\LessonPlanStep4Request;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Models\LessonPlan;
use App\Models\Teacherlink;
use App\Models\TeacherProfile;
use App\Traits\Common;
use Exception;
use Log;
use PDF;

class LessonPlanController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     //
    //     $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
    //     if(Auth::user()->hasRole('principal'))
    //     {
    //         $lessonplan = LessonPlan::with('teacherlink')->whereHas('teacherlink' , function ($query) use($academic_year)
    //         { 
    //             $query->where([
    //                 ['school_id',Auth::user()->school_id],
    //                 ['academic_year_id',$academic_year->id]
    //             ]);
    //         })->where('status','approved')->paginate('10');
    //     }
    //     else
    //     {
    //         $lessonplan = LessonPlan::with('teacherlink')->whereHas('teacherlink' , function ($query) use($academic_year)
    //         { 
    //             $query->where([
    //                 ['school_id',Auth::user()->school_id],
    //                 ['academic_year_id',$academic_year->id],
    //                 ['teacher_id',Auth::id()]
    //             ]);
    //         })->where('status','approved')->paginate('10');
    //     }
    //     $lessonplan = LessonPlanResource::collection($lessonplan);
        
    //     return $lessonplan;
    // }
   public function index(Request $request)
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $query = LessonPlan::with([
                'teacherlink.standardLink',
                'teacherlink.subject',
                'teacherlink.teacher'
            ])
            ->whereHas('teacherlink', function ($q) use ($school_id, $academic_year) {
                $q->where([
                    ['school_id', $school_id],
                    ['academic_year_id', $academic_year->id],
                ]);

                // If not principal → filter by teacher
                if (!Auth::user()->hasRole('principal')) {
                    $q->where('teacher_id', Auth::id());
                }
            });
            // ->where('status', 'approved');

        // Optional filter
        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $lessonplans = $query->orderBy('id', 'desc')->get();

        $grouped = $lessonplans->groupBy(function ($item) {
            return optional($item->teacherlink)->standard_link_id;
        })->map(function ($standardGroup) {

            $first = $standardGroup->first();

            return [
                'standard_id'   => optional($first->teacherlink)->standardLink_id,
                'standard_name' => optional($first->teacherlink?->standardLink)->StandardSection ?? '--',

                'subjects' => $standardGroup->groupBy(function ($item) {
                    return optional($item->teacherlink)->subject_id;
                })->map(function ($subjectGroup) {

                    $firstSubject = $subjectGroup->first();

                    return [
                        'subject_id'   => optional($firstSubject->teacherlink)->subject_id,
                        'subject_name' => optional($firstSubject->teacherlink?->subject)->name ?? '--',

                        'lessonplans'  => LessonPlanResource::collection($subjectGroup->values())
                    ];
                })->values()
            ];
        })->values();

        return response()->json([
            'status'  => true,
            'message' => 'Lesson Plan List',
            'data'    => [
                'standards' => $grouped
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        try
        {
            $lessonplan = LessonPlan::where('id',$id)->first();

            $hour = date('H',strtotime($lessonplan->duration));
            $minutes = date('i',strtotime($lessonplan->duration));
            if($hour == '00')
            {
                $duration = $minutes.' minutes';
            }
            elseif($minutes == '00')
            {
                $duration = $hour.' hours';
            }
            else
            {
                $duration = $hour.' hours '.$minutes.' minutes';
            }

            $array['class']                 =   $lessonplan->teacherlink->standardLink->StandardSection;
            $array['subject']               =   $lessonplan->teacherlink->subject->name;
            $array['unit_no']               =   $lessonplan->unit_no;
            $array['unit_name']             =   $lessonplan->unit_name;
            $array['description']           =   $lessonplan->description;
            $array['title']                 =   $lessonplan->title;
            $array['duration']              =   $duration;
            $array['objective']             =   $lessonplan->objective;
            $array['materials_required']    =   $lessonplan->materials_required;
            $array['introduction']          =   $lessonplan->introduction;
            $array['procedure']             =   $lessonplan->procedure;
            $array['conclusion']            =   $lessonplan->conclusion;
            $array['notes']                 =   $lessonplan->notes;
            $array['assessment']            =   $lessonplan->assessment;
            $array['modification']          =   $lessonplan->modification;

            $pdf = PDF::loadView('/teacher/lessonplan/print', $array);
            
            $folder = Auth::user()->school_id.'/lessonplan';
            $filename = str_replace(' ', '_', $array['title']).'_'.$array['class'].'.pdf';

            $this->putContents($folder.'/'.$filename, $pdf->output());

            $path = $this->getFilePath($folder.'/'.$filename);

            return response()->json([
                'success'   =>  true,
                'message'   =>  'View Lesson Plan',
                'data'      =>  $path
            ],200);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function stepOne(LessonPlanStep1Request $request)
    {
        try {
            $teacherLink = Teacherlink::where([
                ['standardLink_id', $request->standardLink_id],
                ['subject_id', $request->subject_id],
                ['teacher_id', Auth::id()]
            ])->first();

            if (!$teacherLink) {
                return response()->json([
                    'status' => false,
                    'message' => 'Teacher link not found'
                ], 404);
            }

            $lessonplan = new LessonPlan();
            $lessonplan->teacher_link_id = $teacherLink->id;
            $lessonplan->unit_no = $request->unit_no;
            $lessonplan->unit_name = $request->unit_name;
            $lessonplan->title = $request->title;
            $lessonplan->duration = date('H:i:s', mktime(0, $request->duration, 0));
            $lessonplan->description = $request->description;
            $lessonplan->status = 'draft';
            


            $lessonplan->save();

           
            $message = trans('messages.save_success_msg', ['module' => 'Step 1']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $lessonplan,
                Auth::user(),
                ['ip' => $ip, 'details' => request()->userAgent()],
                LOGNAME_ADD_LESSON_PLAN_1,
                $message
            );

        
            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => [
                    'lessonplan_id' => $lessonplan->id
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function stepTwo(LessonPlanStep2Request $request, $id)
    {
        try {
          
            $lessonplan = LessonPlan::find($id);

            // If not found
            if (!$lessonplan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Lesson plan not found'
                ], 404);
            }

            // Update fields
            $lessonplan->objective = $request->objective;
            $lessonplan->materials_required = $request->materials_required;
            $lessonplan->assessment = $request->assessment;
            $lessonplan->status = 'draft';

            $lessonplan->save();

            // Log (optional)
            $message = trans('messages.save_success_msg', ['module' => 'Step 2']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $lessonplan,
                Auth::user(),
                ['ip' => $ip, 'details' => request()->userAgent()],
                LOGNAME_ADD_LESSON_PLAN_2,
                $message
            );

            // API Response
            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => [
                    'lessonplan_id' => $lessonplan->id
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function stepThree(LessonPlanStep3Request $request, $id)
    {
        try {
            // Find lesson plan
            $lessonplan = LessonPlan::find($id);

            if (!$lessonplan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Lesson plan not found'
                ], 404);
            }

            // Update fields
            $lessonplan->introduction = $request->introduction;
            $lessonplan->procedure = $request->procedure;
            $lessonplan->conclusion = $request->conclusion;
            $lessonplan->status = 'pending';

            $lessonplan->save();

            // Notification (safe handling)
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

            $principal = TeacherProfile::with('user')->where([
                ['school_id', Auth::user()->school_id],
                ['academic_year_id', $academic_year->id],
                ['designation', 'principal']
            ])->first();

            if ($principal && $principal->user) {
                $data = [
                    'user' => $principal->user,
                    'details' => trans('notification.lesson_plan_add_success_msg')
                ];

                event(new SingleNotificationEvent($data));
            }

            // Log
            $message = trans('messages.save_success_msg', ['module' => 'Step 3']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $lessonplan,
                Auth::user(),
                ['ip' => $ip, 'details' => request()->userAgent()],
                LOGNAME_ADD_LESSON_PLAN_3,
                $message
            );

            // API Response
            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => [
                    'lessonplan_id' => $lessonplan->id,
                    'status' => $lessonplan->status
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function stepFour(LessonPlanStep4Request $request, $id)
    {
        try {
            // Find lesson plan
            $lessonplan = LessonPlan::find($id);

            if (!$lessonplan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Lesson plan not found'
                ], 404);
            }

            // Update fields
            $lessonplan->notes = $request->notes;
            $lessonplan->modification = $request->modification;
            $lessonplan->status = 'pending';

            $lessonplan->save();

            // Log
            $message = trans('messages.save_success_msg', ['module' => 'Step 4']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $lessonplan,
                Auth::user(),
                ['ip' => $ip, 'details' => request()->userAgent()],
                LOGNAME_ADD_LESSON_PLAN_4,
                $message
            );

            // API Response
            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => [
                    'lessonplan_id' => $lessonplan->id,
                    'status' => $lessonplan->status
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
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

    public function updateStepOne(LessonPlanStep1Request $request,$id)
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
            // API Response
            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => [
                    'lessonplan_id' => $lessonplan->id,
                    'status' => $lessonplan->status
                ]
            ], 200);

            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }
    public function show($id)
    {
        $lessonplan = LessonPlan::with('teacherlink')->where('id', $id)->first();

        if (!$lessonplan) {
            return response()->json([
                'status'  => false,
                'message' => 'Lesson plan not found'
            ], 404);
        }

        $parts = explode(':', $lessonplan->duration ?? '00:00');

        $hours   = isset($parts[0]) ? (int) $parts[0] : 0;
        $minutes = isset($parts[1]) ? (int) $parts[1] : 0;

        $array = [];
        
        $array['id']                    = $lessonplan->id;
        $array['standardLink_id']       = optional($lessonplan->teacherlink)->standardLink_id;
        $array['subject_id']            = optional($lessonplan->teacherlink)->subject_id;
        $array['unit_no']               = $lessonplan->unit_no;
        $array['unit_name']             = $lessonplan->unit_name;
        $array['description']           = $lessonplan->description;
        $array['title']                 = $lessonplan->title;
        $array['duration']              = ($hours * 60) + $minutes;
        $array['objective']             = $lessonplan->objective;
        $array['materials_required']    = $lessonplan->materials_required;
        $array['introduction']          = $lessonplan->introduction;
        $array['procedure']             = $lessonplan->procedure;
        $array['conclusion']            = $lessonplan->conclusion;
        $array['notes']                 = $lessonplan->notes ?? '';
        $array['assessment']            = $lessonplan->assessment;
        $array['modification']          = $lessonplan->modification;
        $array['start_date']            = $lessonplan->start_date;
        $array['end_date']              = $lessonplan->end_date;
        $array['is_published']          = $lessonplan->is_published;
        $array['published_at']          = $lessonplan->published_at;

        return response()->json([
                'status' => true,
                'data' => $array
            ], 200);
    }
}