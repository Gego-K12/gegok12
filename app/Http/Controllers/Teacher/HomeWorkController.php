<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Teacher;

use App\Http\Resources\StandardLink as StandardLinkResource;
use App\Http\Resources\API\Teacher as TeacherResource;
use App\Http\Resources\Homework as HomeworkResource;
use App\Events\Notification\ClassNotificationEvent;
use App\Http\Resources\Subject as SubjectResource;
use App\Http\Requests\HomeworkRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Events\StandardPushEvent;
use Illuminate\Http\Request;
use App\Models\StandardLink;
use App\Traits\LogActivity;
use App\Models\Teacherlink;
use App\Helpers\SiteHelper;
use App\Models\Homework;
use App\Traits\Common;
use Exception;
use Log;

class HomeWorkController extends Controller
{
    //
    use LogActivity;
    use Common;

    public function showList(Request $request)
    {
        //
        $homework = Homework::where('school_id',Auth::user()->school_id)->where('date','>=',date('Y-m-d'))->orderBy('date','DESC')->whereHas('standardLink' , function ($query){
            $query->where('class_teacher_id',Auth::id());
        });
        if(count((array)\Request::getQueryString())>0)
        {
            if($request->showPast == 'true')
            { 
                $homework = $homework->orWhere('date','<',date('Y-m-d'));
            }

            if($request->standardLink_id != '')
            { 
                $homework = $homework->where('standardLink_id',$request->standardLink_id);
            }
        }
        $homework=$homework->get();
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

        $standards = StandardLinkResource::collection($standardLink);

        $teacherlink    =   TeacherLink::get();

        $subjectlist    =   SubjectResource::collection($teacherlink)->groupby('standardLink_id');

        $teacherlist    =   TeacherResource::collection($teacherlink)->groupby(['standardLink_id','subject_id']);

        $array=[];

        $array['standardlist']  =   $standards;
        //$array['subjectlist']   =   $subjectlist;
        //$array['teacherlist']   =   $teacherlist;

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

            $file = $request->file('attachment');
            if($file)
            {
                $folder=Auth::user()->school->slug.'/homework';
                $path = $this->uploadFile($folder,$file);
                $work->attachment = $path; 
            }
            
            $work->save();

            $data=[];

            $data['school_id']      =   Auth::user()->school_id;
            $data['standard_id']    =   $request->standardLink_id;
            $data['message']        =   'New Home Work Added';
            $data['type']           =   'homework';

            event(new StandardPushEvent($data));

            $array = [];

            $array['school_id']         = Auth::user()->school_id;
            $array['standardLink_id']   = $request->standardLink_id;
            $array['details']           = trans('notification.homework_add_success_msg');  

            event(new ClassNotificationEvent($array));  

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
        $standardLink   =   SiteHelper::getStandardLinkList(Auth::user()->school_id);

        $teacherlink    =   TeacherLink::get();

        $subjectlist    =   SubjectResource::collection($teacherlink)->groupby('standardLink_id');

        $teacherlist    =   TeacherResource::collection($teacherlink)->groupby(['standardLink_id','subject_id']);

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
        $array['standardlist']      =   $standardLink;
        //$array['subjectlist']       =   $subjectlist;
        //$array['teacherlist']       =   $teacherlist;
        $array['viewers']           =   count($homework->viewers);

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

            $data=[];

            $data['school_id']      =   Auth::user()->school_id;
            $data['standard_id']    =   $request->standardLink_id;
            $data['message']        =   'Home Work Updated';
            $data['type']           =   'homework';

            event(new StandardPushEvent($data));

            $array = [];

            $array['school_id']         = Auth::user()->school_id;
            $array['standardLink_id']   = $request->standardLink_id;
            $array['details']           = trans('notification.homework_update_success_msg');  

            event(new ClassNotificationEvent($array)); 

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
                $array = [];

                $array['school_id']         = Auth::user()->school_id;
                $array['standardLink_id']   = $homework->standardLink_id;
                $array['details']           = trans('notification.homework_delete_success_msg'); 

                $homework->delete();

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
        }
    } 
}