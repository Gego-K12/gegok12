<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Api\Teacher\Approval;

use App\Http\Resources\API\Teacher\StudentHomework as StudentHomeworkResource;
use App\Http\Resources\API\Teacher\StandardLink as StandardLinkResource;
use App\Http\Resources\API\Teacher\TeacherLink as TeacherLinkResource;
use App\Http\Resources\API\Teacher\Homework as HomeworkResource;
use App\Http\Resources\API\Teacher\Subject as SubjectResource;
use App\Events\Notification\SingleNotificationEvent;
use App\Events\Notification\ClassNotificationEvent;
use App\Http\Requests\HomeworkRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\HomeworkApproval;
use App\Models\StudentHomework;
use Illuminate\Http\Request;
use App\Models\StandardLink;
use App\Traits\LogActivity;
use App\Models\Teacherlink;
use App\Helpers\SiteHelper;
use App\Models\Homework;
use App\Traits\Common;
use App\Models\User;
use Exception;
use Log;

class HomeworkController extends Controller
{
    //
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pendingList(Request $request)
    {
        //
        $school_id      =   Auth::user()->school_id;
        $academic_year  =   SiteHelper::getAcademicYear($school_id);
       /* $homework = Homework::where([['school_id',Auth::user()->school_id],['academic_year_id',$academic_year->id],['teacher_id',Auth::id()],['date','>=',date('Y-m-d')]])->orderBy('date','DESC')->whereHas('homeworkApproval' ,function ($query) {
            $query->where('status','approved');
        })->get();*/
        /*->whereHas('standardLink' , function ($query){
            $query->where('class_teacher_id',Auth::id());
        })*/ // code for class teacher
        $query = Homework::where([
            ['school_id',Auth::user()->school_id],
            ['academic_year_id',$academic_year->id],
            ['teacher_id',Auth::id()],
            ['date','>=',date('Y-m-d')]
        ]);

        //  Filter by standardLink_id (dynamic)
        if (isset($request->standardLink_id)) {
            $query->where('standardLink_id', $request->standardLink_id);
        }

        // Optional: status filter (if needed)
        if (isset($request->status)) {
            $query->where('status', $request->status);
        }
        
        //date filter  
        if (isset($request->date)) {
            $query->whereDate('date', $request->date);
        }

        //subject filter  
        if (isset($request->subject_id)) {
            $query->where('subject_id', $request->subject_id);
        }


        $homework = $query->orderBy('id','desc')->paginate(10);

        $homeworklist = HomeworkResource::collection($homework);
        
        return $homeworklist;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pendingApprovalList()
    {
        //
        $school_id      =   Auth::user()->school_id;
        $academic_year  =   SiteHelper::getAcademicYear($school_id);
        $homework = Homework::where([
            ['school_id',Auth::user()->school_id],
            ['academic_year_id',$academic_year->id],
            ['teacher_id',Auth::id()]
        ])
        ->orderBy('date','DESC')
        ->whereHas('homeworkApproval' ,function ($query) {
            $query->where('status','pending');
        })->get();
        /*->whereHas('standardLink' , function ($query){
            $query->where('class_teacher_id',Auth::id());
        })*/ // code for class teacher

        $homeworklist = HomeworkResource::collection($homework);
        
        return $homeworklist;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rejectedApprovalList()
    {
        //
        $school_id      =   Auth::user()->school_id;
        $academic_year  =   SiteHelper::getAcademicYear($school_id);
        $homework = Homework::where([['school_id',Auth::user()->school_id],['academic_year_id',$academic_year->id],['teacher_id',Auth::id()]])->orderBy('date','DESC')->whereHas('homeworkApproval' ,function ($query) {
            $query->where('status','rejected');
        })->get();
        /*->whereHas('standardLink' , function ($query){
            $query->where('class_teacher_id',Auth::id());
        })*/ // code for class teacher

        $homeworklist = HomeworkResource::collection($homework);
        
        return $homeworklist;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function completedList(Request $request)
    {
        $school_id      =   Auth::user()->school_id;
        $academic_year  =   SiteHelper::getAcademicYear($school_id);

        $query = Homework::where([
            ['school_id',Auth::user()->school_id],
            ['academic_year_id',$academic_year->id],
            ['teacher_id',Auth::id()],
            ['date','<',date('Y-m-d')],
        ]);
        // ->orderBy('date','DESC')
        // ->whereHas('homeworkApproval' ,function ($query) {
        //     $query->where('status','approved');
        // })->paginate(10);

       //  Filter by standardLink_id (dynamic)
        if (isset($request->standardLink_id)) {
            $query->where('standardLink_id', $request->standardLink_id);
        }

        // Optional: status filter (if needed)
        if (isset($request->status)) {
            $query->where('status', $request->status);
        }
        
        //date filter  
        if (isset($request->date)) {
            $query->whereDate('date', $request->date);
        }

        //subject filter  
        if (isset($request->subject_id)) {
            $query->where('subject_id', $request->subject_id);
        }


        $homework = $query->orderBy('id','desc')->paginate(10);

        $homeworklist = HomeworkResource::collection($homework);
        
        return $homeworklist;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $school_id = Auth::user()->school_id;

        $academic_year = SiteHelper::getAcademicYear($school_id);

        $standardLinks = StandardLink::where([
                ['school_id',$school_id],
                ['academic_year_id',$academic_year->id],
                ['class_teacher_id',Auth::id()]
            ])->pluck('id')->toArray();

        $teacherlinks = Teacherlink::where([
            ['school_id',$school_id],
            ['academic_year_id',$academic_year->id],
            ['teacher_id',Auth::id()]
        ])->pluck('standardLink_id')->toArray();

        $standards = array_merge($standardLinks,$teacherlinks);

        $standardLink = StandardLink::whereIn('id',$standards)->get();

        $standards = StandardLinkResource::collection($standardLink);

        return $standards;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function subjectList($standardLink_id)
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $teacherLink = Teacherlink::where([
                ['school_id',Auth::user()->school_id],
                ['academic_year_id',$academic_year->id],
                ['teacher_id',Auth::id()],
                ['standardLink_id',$standardLink_id]
            ])->get();
        $subjectlist = TeacherLinkResource::collection($teacherLink);

        return response()->json([
            'success'   =>  true,
            'message'   =>  'Subject List',
            'data'      =>  $subjectlist
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HomeworkRequest $request)
    {
        //
        try
        {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
          
            $work = new Homework;

            $work->school_id            =   $school_id;
            $work->academic_year_id     =   $academic_year->id;
            $work->standardLink_id      =   $request->standardLink_id;
            $work->subject_id           =   $request->subject_id;
            $work->teacher_id           =   Auth::id();
            $work->description          =   $request->description;
            $work->date                 =   date('Y-m-d',strtotime($request->date));
            $work->submission_date      =   date('Y-m-d',strtotime($request->submission_date));
            $work->status               =   $request->status;

            $file = $request->file('attachment');
            if($file)
            {
                $folder=Auth::user()->school->slug.'/homework';
                $path = $this->uploadFile($folder,$file);
                $work->attachment = $path; 
            }
            
            $work->save();

            $admin = User::BySchool(Auth::user()->school_id)->ByRole(3)->first();

            $homeworkapproval = new HomeworkApproval;

            $homeworkapproval->homework_id    = $work->id;
            // $homeworkapproval->status         = 'pending';
            $homeworkapproval->status         = 'approved';

            $homeworkapproval->save();

            $data = [];

            $admin = User::BySchool(Auth::user()->school_id)->ByRole(3)->first();

            $data['user']       =   $admin;
            $data['details']    =   trans('notification.homework_add_success_msg');

            event(new SingleNotificationEvent($data));

            $message = trans('messages.add_success_msg',['module' => 'Homeworks']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $work,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_HOMEWORK,
                $message
            ); 
            
            return response()->json([
                'success'   =>  true,
                'message'   =>  $message,
            ],200);  
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            dd($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $studentHomeworks = StudentHomework::where('homework_id',$id)->paginate(10);

        $studentHomeworks = StudentHomeworkResource::collection($studentHomeworks);

        return $studentHomeworks;
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
        $homework = Homework::where('id',$id)->first();
        if($homework)
        {
            $array=[];

            $array['standardLink_id']   =   $homework->standardLink_id;
            $array['subject_id']        =   $homework->subject_id;
            $array['description']       =   $homework->description;
            $array['date']              =   date('d-m-Y',strtotime($homework->date));
            $array['attachment']        =   $homework->attachment == null ? '':$homework->AttachmentPath;
            $array['pending_count']     =   $homework->PendingCount;
            $array['submission_date']   =   date('d-m-Y',strtotime($homework->submission_date));
            $array['status']            =   $homework->status;

            return $array;
        }
        else
        {
            return response()->json([
                'success'   =>  false,
                'message'   =>  'Unauthorized',
            ],401); 
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HomeworkRequest $request, $id)
    {
        //
        try
        {
            $work = Homework::where('id',$id)->first();
            // if( $work->homeworkApproval->status == 'pending' )
            // {
                $work->standardLink_id      =   $request->standardLink_id;
                $work->subject_id           =   $request->subject_id;
                $work->teacher_id           =   Auth::id();
                $work->description          =   $request->description;
                $work->date                 =   date('Y-m-d',strtotime($request->date));
                $work->submission_date      =   date('Y-m-d',strtotime($request->submission_date));

                $file = $request->file('attachment');
                if($file)
                {
                    $folder=Auth::user()->school->slug.'/homework';
                    $path = $this->uploadFile($folder,$file);
                    $work->attachment = $path; 
                }
                else
                {
                    $work->attachment=$work->attachment; 
                }
                
                $work->save();

                $admin = User::BySchool(Auth::user()->school_id)->ByRole(3)->first();
                $data = [];

                $data['user']       =   $admin;
                $data['details']    =   trans('notification.homework_update_success_msg');

                event(new SingleNotificationEvent($data));

                $message = trans('messages.update_success_msg',['module' => 'Homeworks']);

                $ip= $this->getRequestIP();
                $this->doActivityLog(
                    $work,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                    LOGNAME_EDIT_HOMEWORK,
                    $message
                );
            // }
            // else
            // {
            //     $message = trans('messages.approval_done_msg',['module' => 'Homework']);
            // }
            
            return response()->json([
                'success'   =>  true,
                'message'   =>  $message,
            ],200); 
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            dd($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try
        {
            $homework = Homework::where('id',$id)->first();
            if($homework)
            {
                if(\Gate::allows('homework',$homework))
                {                    
                    // if( $homework->homeworkApproval->status == 'pending' )
                    // {
                        $homework->delete();

                        $admin = User::BySchool(Auth::user()->school_id)->ByRole(3)->first();

                        if($admin->id != Auth::id())
                        {
                            $data = [];

                            $data['user']       =   $admin;
                            $data['details']    =   trans('notification.homework_delete_success_msg');

                            event(new SingleNotificationEvent($data));

                            /*$array=[];

                            $array['school_id'] =  Auth::user()->school_id;
                            $array['user_id']   =  $admin->id;
                            $array['message']   = 'Assignment Deleted';
                            $array['type']      = 'assignment';
                                    
                            event(new SinglePushEvent($array));*/
                        }

                        $message=trans('messages.delete_success_msg',['module' => 'Homework']); 

                        event(new ClassNotificationEvent($array));

                        $ip= $this->getRequestIP();
                        $this->doActivityLog(
                            $homework,
                            Auth::user(),
                            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                            LOGNAME_DELETE_HOMEWORK,
                            $message
                        );
                    // }
                    // else
                    // {
                    //     $message = trans('messages.delete_fail_approval_done_msg',['module' => 'Homework']);
                    // }
                    $success = true;
                    $error_code = 200;
                }
                else
                {
                    abort(403);
                }
            }
            else
            {
                $success    =   false;
                $message    =   'Unauthorized';
                $error_code =   401; 
            }
            return response()->json([
                'success'   =>  $success,
                'message'   =>  $message,
            ],$error_code); 
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            dd($e->getMessage());
        }
    }
}