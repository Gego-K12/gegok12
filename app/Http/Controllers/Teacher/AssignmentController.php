<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Teacher;

use App\Http\Resources\Assignment as AssignmentResource;
use App\Events\Notification\ClassNotificationEvent;
use App\Http\Requests\AssignmentUpdateRequest;
use App\Http\Requests\AssignmentAddRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Events\StandardPushEvent;
use App\Models\StudentAcademic;
use Illuminate\Http\Request;
use App\Traits\EventProcess;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Models\Teacherlink;
use App\Models\Assignment;
use App\Traits\Common;
use App\Models\User;
use Exception;

class AssignmentController extends Controller
{
    use EventProcess;
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showList(Request $request)
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $assignment = Assignment::where([
                ['school_id',Auth::user()->school_id],
                ['academic_year_id',$academic_year->id],
                ['teacher_id',Auth::id()],
                ['submission_date','>=',date('Y-m-d')],
                ['status','ongoing']
            ])->orWhere([['status','pending'],['teacher_id',Auth::id()]]);
        if(count((array)\Request::getQueryString())>0)
        {
            if($request->showCompleted == 'true')
            { 
                $assignment = $assignment->orWhere('status','completed')->where('submission_date','<=',date('Y-m-d'));
            }
        }
        $assignment=$assignment->get();
        $assignmentlist = AssignmentResource::collection($assignment);
        
        return $assignmentlist;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = \Request::getQueryString();

        return view('/teacher/assignment/index' ,['query' => $query]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
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
        return view('/teacher/assignment/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AssignmentAddRequest $request)
    {
        //
        try
        {
            $school_id  =   Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $assignment     =   new Assignment;

            $assignment->school_id          =   $school_id;
            $assignment->academic_year_id   =   $academic_year->id;
            $assignment->standardLink_id    =   $request->standardLink_id;
            $assignment->subject_id         =   $request->subject_id;
            $assignment->teacher_id         =   Auth::id();
            $assignment->title              =   $request->title;
            $assignment->description        =   $request->description;

            $file   =   $request->file('attachment');
            if($file)
            {
                $path = $this->uploadFile(Auth::user()->school->slug.'/uploads/teacher/assignment',$file);
                $assignment->attachment = $path; 
            }

            $assignment->marks              =   $request->marks;
            $assignment->assigned_date      =   date('Y-m-d',strtotime($request->assigned_date));
            $assignment->submission_date    =   date('Y-m-d',strtotime($request->submission_date));
            $assignment->status             = $request->status;
            // if($request->assigned_date == date('Y-m-d'))
            // {
            //     $assignment->status             =   'ongoing';
            // }
            // else
            // {
            //     $assignment->status             =   'pending';
            // }

            $assignment->save();

            $data=[];

            $data['school_id']      =   $school_id;
            $data['standard_id']    =   $assignment->standardLink_id;
            $data['message']        =   'New Assignment Added';
            $data['type']           =   'assignment';

            event(new StandardPushEvent($data));

            $array = [];

            $array['school_id']         = $school_id;
            $array['standardLink_id']   = $assignment->standardLink_id;
            $array['details']           = trans('notification.teacher_assignment_add_msg');  

            event(new ClassNotificationEvent($array));         

            $studentAcademics = StudentAcademic::where([
                ['school_id',$school_id],
                ['academic_year_id',$academic_year->id],
                ['standardLink_id',$assignment->standardLink_id]
            ])->get();
            foreach($studentAcademics as $studentAcademic)
            {
                foreach ($studentAcademic->user->parents as $parent) 
                {
                    $this->sendToAssignmentReminder($school_id,date('Y-m-d',strtotime($request->submission_date)),$assignment->subject->name,$assignment->title,$parent->userParent->id,$parent->userParent->email,$parent->userParent->mobile_no);
                }  
            }

            $message=trans('messages.add_success_msg',['module' => 'Assignment']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $assignment,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_ASSIGNMENT,
                $message
            );

            $res['success'] = $message;

            return $res;
        }
        catch(Exception $e)
        {
            //dd($e->getMessage());
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
        $assignment     =   Assignment::where('id',$id)->first();

        $array  =   [];

        $array['title']             =   $assignment->title;
        $array['description']       =   $assignment->description;
        $array['marks']             =   $assignment->marks;
        $array['assigned_date']     =   date('Y-m-d',strtotime($assignment->assigned_date));
        $array['submission_date']   =   date('Y-m-d',strtotime($assignment->submission_date));
        $array['attachment']        =   $assignment->attachment==null ? '':$assignment->attachment;
        $array['status']        =   $assignment->status;

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
        $assignment     =   Assignment::where('id',$id)->first();
        if($assignment->status != 'ongoing')
        {
            return view('/teacher/assignment/edit' , ['assignment' => $assignment]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AssignmentUpdateRequest $request, $id)
    {
        //
        try
        {
            $assignment     =   Assignment::where('id',$id)->first();

            $assignment->title              =   $request->title;
            $assignment->description        =   $request->description;

            $file   =   $request->file('attachment');
            if($file)
            {
                $path = $this->uploadFile(Auth::user()->school->slug.'/uploads/teacher/assignment',$file);
                $assignment->attachment = $path; 
            }
            else
            {
                $assignment->attachment=$assignment->attachment; 
            }


            $assignment->marks              =   $request->marks;
            $assignment->assigned_date      =   date('Y-m-d',strtotime($request->assigned_date));
            $assignment->submission_date    =   date('Y-m-d',strtotime($request->submission_date));
            $assignment->status             = $request->status;
            // if($request->assigned_date == date('Y-m-d'))
            // {
            //     $assignment->status             =   'ongoing';
            // }
            // else
            // {
            //     $assignment->status             =   'pending';
            // }

            $assignment->save();

            $data=[];

            $data['school_id']      =   Auth::user()->school_id;
            $data['standard_id']    =   $assignment->standardLink_id;
            $data['message']        =   'Assignment Updated';
            $data['type']           =   'assignment';

            event(new StandardPushEvent($data));

            $array = [];

            $array['school_id']         = Auth::user()->school_id;
            $array['standardLink_id']   = $assignment->standardLink_id;
            $array['details']           = trans('notification.teacher_assignment_update_msg');  

            event(new ClassNotificationEvent($array));   

            $message=trans('messages.update_success_msg',['module' => 'Assignment']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $assignment,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_ASSIGNMENT,
                $message
            );

            $res['success'] = $message;

            return $res;

        }
        catch(Exception $e)
        {
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
        //
        try
        {
            $assignment = Assignment::where('id',$id)->first();
            if(Gate::allows('assignment',$assignment))
            {
                $assignment->status     =   'cancel';
                $assignment->save();
                $array = [];

                $array['school_id']         = $assignment->school_id;
                $array['standardLink_id']   = $assignment->standardLink_id;
                $array['details']           = trans('notification.teacher_assignment_delete_msg');

                $assignment->delete();
  
                event(new ClassNotificationEvent($array));  

                $message=trans('messages.delete_success_msg',['module' => 'Assignment']);


                $ip= $this->getRequestIP();
                $this->doActivityLog(
                    $assignment,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                    LOGNAME_DELETE_ASSIGNMENT,
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
            //dd($e->getMessage());
        }
    }
}