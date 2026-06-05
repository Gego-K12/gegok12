<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Teacher\Approval;

use App\Http\Resources\StandardLink as StandardLinkResource;
use App\Http\Resources\Assignment as AssignmentResource;
use App\Events\Notification\SingleNotificationEvent;
use App\Http\Resources\Subject as SubjectResource;
use App\Http\Requests\AssignmentUpdateRequest;
use App\Http\Requests\AssignmentAddRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\AssignmentApproval;
use App\Events\SinglePushEvent;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use App\Models\StandardLink;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Models\Teacherlink;
use App\Models\Assignment;
use App\Traits\Common;
use App\Models\User;
use Exception;
use Log;

class AssignmentController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showList(Request $request, $status)
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        if(Auth::user()->hasRole('principal'))
        {
            $assignment = Assignment::where([
                    ['school_id',Auth::user()->school_id],
                    ['academic_year_id',$academic_year->id],
                    ['status',$status]
                ]);
            // ->whereHas('assignmentApproval' , function($query) use($status) {
            //         $query->where('status',$status);
            //     });
        }
        else
        {
            $assignment = Assignment::where([
                    ['school_id',Auth::user()->school_id],
                    ['academic_year_id',$academic_year->id],
                    ['teacher_id',Auth::id()],
                    ['status',$status]
                ]);

            $approve_status =$status;
            if($status=='ongoing')
            {
                $approve_status ='approved';

            }

            $assignment=$assignment->whereHas('assignmentApproval' , function($query) use($approve_status) {
                    $query->where('status',$approve_status);
                });
        }
       
        if(count((array)\Request::getQueryString())>0)
        {
            if($request->standardLink_id != '')
            { 
                $assignment = $assignment->where('standardLink_id',$request->standardLink_id);
            }

            if($request->search != null)
            {
                $assignment = $assignment->where('title','LIKE','%'.$request->search.'%')->orWhere('description','LIKE','%'.$request->search.'%')->orWhere('marks','LIKE','%'.$request->search.'%')->orWhereHas('subject' , function ($query) use($request)
                {
                    $query->where('name','LIKE','%'.$request->search.'%');
                });
            }
        }
        $assignment=$assignment->orderBy('id','DESC')->paginate(10);
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
        if(Auth::user()->hasRole('principal'))
        {
            $role = 'principal';
        }
        $query = \Request::getQueryString();

        return view('/teacher/assignment/index' ,['role' => $role , 'query' => $query]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {

        /*if(Auth::user()->hasRole('principal'))
        {
            $array['standardLinklist'] = SiteHelper::getStandardLinkList(Auth::user()->school_id);
        }
        else
        {*/
            $array = SiteHelper::getStandardSubjectList(Auth::user()->school_id,Auth::id());
        //}
        
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
        // dd('hii');
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
            $assignment->assigned_date = $request->assigned_date ? date('Y-m-d', strtotime($request->assigned_date)) : null;

            $assignment->submission_date = !empty($request->submission_date) ? date('Y-m-d', strtotime($request->submission_date)) : null;
            
            $assignment->status             =   $request->status;


            // if($assignment->assigned_date == date('Y-m-d'))
            // {
            //     $assignment->status             =   'ongoing';
            // }
            // else
            // {
            //     $assignment->status             =   'pending';
            // }
   

            $assignment->save();

            $assignmentapproval = new AssignmentApproval;

            $assignmentapproval->assignment_id  = $assignment->id;

            $status_approval='approved';
            if(config('settings.assignment_status') == 1)
            {
                $status_approval='pending';
            }

            $assignmentapproval->status         = $status_approval;

            $assignmentapproval->save();

            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $principal = TeacherProfile::with('user')->where([['school_id',Auth::user()->school_id],['academic_year_id',$academic_year->id],['designation','principal']])->first();

            $data = [];

            $data['user']       =   $principal->user;
            $data['details']    =   trans('notification.teacher_assignment_add_msg');

            event(new SingleNotificationEvent($data));

            $array=[];

            $array['school_id']  = $assignment->school_id;
            $array['message']    = 'New Assignment Added';
            $array['type']       = 'assignment';
                    
            event(new SinglePushEvent($array));

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
            Log::info($e->getMessage());
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

    public function view($id)
    {
        $assignment = Assignment::where('id',$id)->first();
        $viewers=$assignment->viewers()->latest()->paginate(10);

        return view('/teacher/assignment/view',['assignment' => $assignment,'viewers' => $viewers]); 
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
        if($assignment->status != 'completed')
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
            $assignment->assigned_date = $request->assigned_date ? date('Y-m-d', strtotime($request->assigned_date)) : null;

            $assignment->submission_date = !empty($request->submission_date) ? date('Y-m-d', strtotime($request->submission_date)) : null;
            
            $assignment->status             =   $request->status;
            // if($assigned_date == date('Y-m-d'))
            // {
            //     $assignment->status             =   'ongoing';
            // }
            // else
            // {
            //     $assignment->status             =   'pending';
            // }

            $assignment->save(); 

            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $principal = TeacherProfile::with('user')->where([['school_id',Auth::user()->school_id],['academic_year_id',$academic_year->id],['designation','principal']])->first();

            $data = [];

            $data['user']       =   $principal->user;
            $data['details']    =   trans('notification.teacher_assignment_update_msg');

            event(new SingleNotificationEvent($data));

            $array=[];

            $array['school_id']  =  Auth::user()->school_id;
            $array['message']    = 'Assignment Updated';
            $array['type']       = 'assignment';
                    
            event(new SinglePushEvent($array)); 

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
        //
        try
        {
            $assignment = Assignment::where('id',$id)->first();
            if(Gate::allows('assignment',$assignment))
            {
                $assignment->status     =   'cancel';
                $assignment->save();

                $assignment->delete();                

                $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
                $principal = TeacherProfile::with('user')->where([['school_id',Auth::user()->school_id],['academic_year_id',$academic_year->id],['designation','principal']])->first();

                $data = [];

                $data['user']       =   $principal->user;
                $data['details']    =   trans('notification.teacher_assignment_delete_msg');

                event(new SingleNotificationEvent($data));

                $array=[];

                $array['school_id']  =  Auth::user()->school_id;
                $array['message']    = 'Assignment Deleted';
                $array['type']       = 'assignment';
                        
                event(new SinglePushEvent($array)); 
 
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
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }
}