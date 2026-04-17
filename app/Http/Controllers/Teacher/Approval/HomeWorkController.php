<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Teacher\Approval;

use App\Http\Resources\StandardLink as StandardLinkResource;
use App\Http\Resources\API\Teacher as TeacherResource;
use App\Http\Resources\Homework as HomeworkResource;
use App\Events\Notification\SingleNotificationEvent;
use App\Http\Resources\Subject as SubjectResource;
use App\Http\Requests\HomeworkRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\HomeworkApproval;
use App\Events\SinglePushEvent;
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

class HomeWorkController extends Controller
{
    //
    use LogActivity;
    use Common;

    public function showList(Request $request,$status)
    {
        //
        $admin = Auth::id();
        $school_id      =   Auth::user()->school_id;
        $academic_year  =   SiteHelper::getAcademicYear($school_id);

        $homework = Homework::where([
            ['school_id',$school_id],
            ['academic_year_id',$academic_year->id],
            ['teacher_id',Auth::id()]
        ])
        ->where('status',$status)
        ->orderBy('date','DESC');
        // ->whereHas('homeworkApproval' ,function ($query) use ($status) {
        //     $query->where('status',$status);
        // });
        /*->orWhereHas('standardLink' , function ($q) use ($admin){
            $q->where('class_teacher_id',$admin);
        })*/ // code for class teacher
        if(count((array)\Request::getQueryString())>0)
        {
            if($request->standardLink_id != '')
            { 
                $homework = $homework->where('standardLink_id',$request->standardLink_id);
            }

            if($request->search != '')
            { 
                $homework = $homework->where('description','LIKE','%'.$request->search.'%')->orWhereHas('subject',function ($q) use($request){
                    $q->where('name','LIKE','%'.$request->search.'%');
                });
            }
        }
        $homework=$homework->orderBy('id','DESC')->paginate(10);
        $homeworklist = HomeworkResource::collection($homework);
        
        return $homeworklist;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $query = \Request::getQueryString();

        return view('/teacher/homework/index' ,['query' => $query]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
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

        $teacherlink    =   Teacherlink::where([['school_id',$school_id],['academic_year_id',$academic_year->id],['teacher_id',Auth::id()]])->whereIn('standardLink_id',$standards)->get();

        $standards = StandardLinkResource::collection($standardLink);

        $subjectlist =   SubjectResource::collection($teacherlink)->groupby('standardLink_id');

        $array=[];

        $array['standardlist']  =   $standards;
        $array['subjectlist']   =   $subjectlist;

        return $array;
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $standard = \Request::get('standardLink_id') ? \Request::get('standardLink_id'):'';
        return view('/teacher/homework/create',['standard' => $standard]); 
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

            $homeworkapproval = new HomeworkApproval;

            $admin = User::BySchool(Auth::user()->school_id)->ByRole(3)->first();

            if($admin->id != Auth::id())
            {
                $homeworkapproval->homework_id  = $work->id;
                $homeworkapproval->status       = 'pending';

                $data = [];

                $data['user']       =   $admin;
                $data['details']    =   trans('notification.homework_add_success_msg');

                event(new SingleNotificationEvent($data));
            }
            else
            {
                $homeworkapproval->homework_id    = $work->id;
                $homeworkapproval->comments       = 'Created By Admin';
                $homeworkapproval->approved_by    = Auth::id();
                $homeworkapproval->approved_at    = date('Y-m-d');
                $homeworkapproval->status         = 'approved';
            }
            $homeworkapproval->save();

            $message = trans('messages.add_success_msg',['module' => 'Homeworks']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $work,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_HOMEWORK,
                $message
            );

            $res['success'] = $message;
            return $res;  
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $homework = Homework::where('id',$id)->first();
        return view('/teacher/homework/show',['homework' => $homework]); 
    }

    public function view($id)
    {
        $homework = Homework::where('id',$id)->first();
        $viewers=$homework->viewers()->latest()->paginate(10);
       // dd($viewers);

        return view('/teacher/homework/view',['homework' => $homework,'viewers' => $viewers]); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $homework = Homework::where('id',$id)->first();
        return view('/teacher/homework/edit',['homework' => $homework]); 
    }

    public function editList($id)
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

        $teacherlink    =   Teacherlink::where([['school_id',$school_id],['academic_year_id',$academic_year->id],['teacher_id',Auth::id()]])->whereIn('standardLink_id',$standards)->get();

        $standards = StandardLinkResource::collection($standardLink);

        $subjectlist = SubjectResource::collection($teacherlink)->groupby('standardLink_id');

        $homework = Homework::where('id',$id)->first();

        $array=[];

        $array['standardLink_id']   =   $homework->standardLink_id;
        $array['standardLink_name'] =   $homework->standardLink->StandardSection;
        $array['pending_count']     =   $homework->PendingCount;
        $array['subject_id']        =   $homework->subject_id;
        $array['teacher_id']        =   $homework->teacher_id;
        $array['description']       =   $homework->description;
        $array['date']              =   date('Y-m-d',strtotime($homework->date));
        $array['attachment']        =   $homework->attachment==null ? '':$homework->AttachmentPath;
        $array['standardlist']      =   $standards;
        $array['subjectlist']       =   $subjectlist;
        $array['viewers']           =   count($homework->viewers);
        $array['submission_date']   =   date('Y-m-d',strtotime($homework->submission_date));

        $array['status']            =   $homework->status;

        return $array;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(HomeworkRequest $request,$id)
    {
        //
        try
        {
            $work = Homework::where('id',$id)->first();

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
            else
            {
                $work->attachment=$request->attachment; 
            }
            
            $work->save();

            $admin = User::BySchool(Auth::user()->school_id)->ByRole(3)->first();

            if($admin->id != Auth::id())
            {
                $data = [];

                $data['user']       =   $admin;
                $data['details']    =   trans('notification.homework_update_success_msg');

                event(new SingleNotificationEvent($data));
            }

            $message = trans('messages.update_success_msg',['module' => 'Homeworks']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $work,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_HOMEWORK,
                $message
            );

            $res['success'] = $message;
            return $res;  
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
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
        try
        {
            $homework = Homework::where('id',$id)->first();
            if(Gate::allows('homework',$homework))
            {
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

                $ip= $this->getRequestIP();
                $this->doActivityLog(
                    $homework,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                    LOGNAME_DELETE_HOMEWORK,
                    $message
                );
                $res['success'] = $message;
                return $res;
            }
            else
            {
                abort(403);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    } 
}