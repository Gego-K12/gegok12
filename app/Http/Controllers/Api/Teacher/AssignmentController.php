<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Api\Teacher;

use App\Http\Resources\API\Teacher\AssignmentTeacher as AssignmentTeacherResource;
use App\Http\Resources\API\Teacher\TeacherLink as TeacherLinkResource;
use App\Http\Resources\API\Teacher\Assignment as AssignmentResource;
use App\Http\Resources\API\Teacher\StandardLink as StandardLinkResource;
use App\Http\Requests\API\Teacher\AssignmentUpdateRequest;
use App\Http\Requests\API\Teacher\AssignmentAddRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Events\StandardPushEvent;
use App\Models\StudentAcademic;
use App\Traits\EventProcess;
use Illuminate\Http\Request;
use App\Helpers\SiteHelper;
use App\Traits\LogActivity;
use App\Models\Teacherlink;
use App\Models\StandardLink;
use App\Models\Assignment;
use App\Traits\Common;
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
    public function assignment()
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $assignment = Assignment::where([
                ['school_id',Auth::user()->school_id],
                ['academic_year_id',$academic_year->id],
                ['teacher_id',Auth::id()],
                ['submission_date','>=',date('Y-m-d')],
                ['status','ongoing']
            ])->orWhere([['status','pending'],['teacher_id',Auth::id()]])->get();
        $assignmentlist = AssignmentResource::collection($assignment);

        return response()->json([
            'success'   =>  true,
            'message'   =>  'Assignment List',
            'data'      =>  $assignmentlist
        ],200);
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
                ['submission_date','<=',date('Y-m-d')],
                ['status','completed']
            ])->get();
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
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

         $standardLinks = StandardLink::where([
                ['school_id',$school_id],
                ['academic_year_id',$academic_year->id],
                ['class_teacher_id',$teacher_id]
            ])->pluck('id')->toArray();

        $teacherlinks = Teacherlink::where([
            ['school_id',$school_id],
            ['academic_year_id',$academic_year->id],
            ['teacher_id',$teacher_id]
        ])->pluck('standardLink_id')->toArray();

        $standards = array_merge($standardLinks,$teacherlinks);

        $standardLink = StandardLink::whereIn('id',$standards)->get();

        $standards = StandardLinkResource::collection($standardLink);

        $teacherLink = Teacherlink::where([
                ['school_id',Auth::user()->school_id],
                ['academic_year_id',$academic_year->id],
                ['teacher_id',Auth::id()]
            ])->get();
        $standardLinklist = $standards;
        $subjectlist = TeacherLinkResource::collection($teacherLink)->groupBy('standardLink_id');

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
            $assignment->assigned_date      =   date('Y-m-d',strtotime($request->assigned_date));
            $assignment->submission_date    =   date('Y-m-d',strtotime($request->submission_date));
            if($request->assigned_date == date('Y-m-d'))
            {
                $assignment->status             =   'ongoing';
            }
            else
            {
                $assignment->status             =   'pending';
            }

            $assignment->save();

            $data=[];

            $data['school_id']      =   $school_id;
            $data['standard_id']    =   $assignment->standardLink_id;
            $data['message']        =   'New Assignment Added';
            $data['type']           =   'assignment';

            event(new StandardPushEvent($data));

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

            return response()->json([
                'success'   =>  true,
                'message'   =>  $message,
            ],200);
        }
        catch(Exception $e)
        {
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
                $assignment->assigned_date      =   date('Y-m-d',strtotime($request->assigned_date));
                $assignment->submission_date    =   date('Y-m-d',strtotime($request->submission_date));
                if($request->assigned_date == date('Y-m-d'))
                {
                    $assignment->status             =   'ongoing';
                }
                else
                {
                    $assignment->status             =   'pending';
                }

                $assignment->save();

                $data=[];

                $data['school_id']      =   $school_id;
                $data['standard_id']    =   $assignment->standardLink_id;
                $data['message']        =   'Assignment Updated';
                $data['type']           =   'assignment';

                event(new StandardPushEvent($data));

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

            return response()->json([
                'success'   =>  true,
                'message'   =>  $message,
            ],200);
        }
        catch(Exception $e)
        {
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
            $assignment->status     =   'cancel';
            $assignment->save();

            $assignment->delete();

            $message=trans('messages.delete_success_msg',['module' => 'Assignment']);


            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $assignment,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_ASSIGNMENT,
                $message
            );

            return response()->json([
                'success'   =>  true,
                'message'   =>  $message,
            ],200);
        }
        catch(Exception $e)
        {
        }
    }
}