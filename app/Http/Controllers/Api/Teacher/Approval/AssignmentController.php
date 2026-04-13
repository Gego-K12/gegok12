<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Api\Teacher\Approval;

use App\Http\Resources\API\Teacher\AssignmentTeacher as AssignmentTeacherResource;
use App\Http\Resources\API\Teacher\StandardLink as StandardLinkResource;
use App\Http\Resources\API\Teacher\TeacherLink as TeacherLinkResource;
use App\Http\Resources\API\Teacher\Assignment as AssignmentResource;
use App\Http\Requests\API\Teacher\AssignmentUpdateRequest;
use App\Http\Requests\API\Teacher\AssignmentAddRequest;
use App\Events\Notification\SingleNotificationEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AssignmentApproval;
use App\Events\SinglePushEvent;
use App\Models\StudentAcademic;
use App\Models\TeacherProfile;
use App\Traits\EventProcess;
use Illuminate\Http\Request;
use App\Helpers\SiteHelper;
use App\Traits\LogActivity;
use App\Models\Teacherlink;
use App\Models\Assignment;
use App\Models\StandardLink;
use App\Traits\Common;
use Exception;
use Log;

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
    public function assignment(Request $request)
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        
        //  $assignment = Assignment::where([
        //         ['school_id',Auth::user()->school_id],
        //         ['academic_year_id',$academic_year->id],
        //         ['teacher_id',Auth::id()],
        //         ['submission_date','>=',date('Y-m-d')],
        //     ])->latest()->get();   
        // $assignmentlist = AssignmentResource::collection($assignment);

        // return response()->json([
        //     'success'   =>  true,
        //     'message'   =>  'Assignment List',
        //     'data'      =>  $assignmentlist
        // ],200);

        $query = Assignment::where([
            ['school_id', Auth::user()->school_id],
            ['academic_year_id', $academic_year->id],
            ['teacher_id', Auth::id()],
            // ['submission_date', '>=', date('Y-m-d')],
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
            $query->whereDate('submission_date', $request->date);
        }


        $assignment = $query->orderBy('id','desc')->paginate(10);

        //  Resource collection
        $assignmentlist = AssignmentResource::collection($assignment);

        return response()->json([
            'success' => true,
            'message' => 'Assignment List',
            'data'    => $assignmentlist,
            'meta'    => [
                'current_page' => $assignment->currentPage(),
                'last_page'    => $assignment->lastPage(),
                'per_page'     => $assignment->perPage(),
                'total'        => $assignment->total(),
            ]
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function completedAssignment()
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $assignment = Assignment::where([
                ['school_id',Auth::user()->school_id],
                ['academic_year_id',$academic_year->id],
                ['teacher_id',Auth::id()],
                // ['submission_date','<=',date('Y-m-d')],
                ['status','completed']
            ])->whereHas('assignmentApproval' , function($query) use($status) {
                $query->where('status','approved');
            })->get();
        $assignmentlist = AssignmentResource::collection($assignment);

        return response()->json([
            'success'   =>  true,
            'message'   =>  'Completed Assignment List',
            'data'      =>  $assignmentlist
        ],200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function standardLinkList()
    {
        //
       /* $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $teacherLink = Teacherlink::where([
                ['school_id',Auth::user()->school_id],
                ['academic_year_id',$academic_year->id],
                ['teacher_id',Auth::id()]
            ])->get();
        $standardLinklist = AssignmentTeacherResource::collection($teacherLink);
        $subjectlist = TeacherLinkResource::collection($teacherLink)->groupBy('standardLink_id');*/

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

        dd($teacherlinks);

        $standards = array_merge($standardLinks,$teacherlinks);

        $standardLink = StandardLink::whereIn('id',$standards)->get();

        $standardLinklist = StandardLinkResource::collection($standardLink);

        return response()->json([
            'success'   =>  true,
            'message'   =>  'StandardLink List',
            'data'      =>  $standardLinklist
        ],200);
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
            $assigned_date = date('Y-m-d',strtotime($request->assigned_date));
            $assignment->assigned_date      =   $assigned_date;
            $assignment->submission_date    =   date('Y-m-d',strtotime($request->submission_date));
            
            $assignment->status             =   $request->status;

            if ($request->status == 'completed')
            {
                $assignment->status             =   'ongoing';

            }
            // if($assigned_date == date('Y-m-d'))
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
            // $assignmentapproval->status         = 'pending';
            // $assignmentapproval->status         = $request->status;

            // if ($request->status == 'completed')
            // {
                $assignmentapproval->status         = 'approved';
            // }

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
        $assignment     =   Assignment::where('id',$id)->first();

        $array  =   [];

        $array['title']             =   $assignment->title;
        $array['description']       =   $assignment->description;
        $array['marks']             =   $assignment->marks;
        $array['assignedDate']      =   date('d-m-Y',strtotime($assignment->assigned_date));
        $array['submissionDate']    =   date('d-m-Y',strtotime($assignment->submission_date));
        $array['attachment']        =   $assignment->attachment==null ? '':$assignment->AttachmentPath;

        $array['status']        =   $assignment->status;

        return response()->json([
            'success'   =>  true,
            'message'   =>  'Show Assignment',
            'data'      =>  $array,
        ],200);
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
            // if( $assignment->assignmentApproval->status == 'pending' )
            // {
                if($assignment->status != 'completed')
                {

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
                    $assigned_date      =   date('Y-m-d',strtotime($request->assigned_date));
                    $assignment->assigned_date      =   $assigned_date;
                    $assignment->submission_date    =   date('Y-m-d',strtotime($request->submission_date));
                    // if($assigned_date == date('Y-m-d'))
                    // {
                    //     $assignment->status             =   'ongoing';
                    // }
                    // else
                    // {
                    //     $assignment->status             =   'pending';
                    // }
                    if ($request->status == 'completed')
                    {
                        $assignment->status             =   'ongoing';

                    }

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
                }
                else
                {
                    $message = "Can't Update.Assignment is completed";
                }
            // }
            // else
            // {
            //     $message = trans('messages.approval_done_msg',['module' => 'Assignment']);
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
            $assignment = Assignment::where('id',$id)->first();
            if( $assignment->assignmentApproval->status == 'pending' )
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
            }
            else
            {
                $message = trans('messages.delete_fail_approval_done_msg',['module' => 'Assignment']);
            }

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
}