<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Api;

use App\Http\Resources\API\Assignment as AssignmentResource;
use App\Http\Requests\API\StudentAssignmentAddRequest;
use App\Events\Notification\SingleNotificationEvent;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\StudentAssignment;
use App\Events\SinglePushEvent;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
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
    public function index(Request $request,$student_id)
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $student = User::where('id',$student_id)->first();

        $query = Assignment::where([
                ['school_id',Auth::user()->school_id],
                ['academic_year_id',$academic_year->id],
                ['standardLink_id',$student->studentAcademicLatest->standardLink_id],
                ['submission_date','>=',date('Y-m-d')],
                ['status','ongoing']
            ]);

        //date filter  
        if (isset($request->date)) {
            $query->whereDate('assigned_date', $request->date);
        }

        //subject filter  
        if (isset($request->subject_id)) {
            $query->where('subject_id', $request->subject_id);
        }

        $assignments = $query->orderBy('submission_date','ASC')->paginate(10);

        $array = [];
        //$assignment = AssignmentResource::collection($assignment);
        foreach ($assignments as $key => $assignment) 
        {

            $array[$key]['id']               =  $assignment->id;
        $array[$key]['subject_name']         =  $assignment->subject->name;
             $array[$key]['teacher_name']    =  $assignment->teacher->fullname;
            $array[$key]['class']            =  $assignment->standardLink->StandardSection;
            $array[$key]['title']            =  $assignment->title;
            $array[$key]['description']      =  $assignment->description;
            $array[$key]['assignedDate']     =  $assignment->assigned_date=='' ? null:date('d-m-Y', strtotime($assignment->assigned_date));
            $array[$key]['submissionDate']   =  $assignment->submission_date=='' ? null:date('d-m-Y', strtotime($assignment->submission_date));
            $array[$key]['attachment']       =  $assignment->attachment == '' ? '':$assignment->AttachmentPath;
            $array[$key]['status']           =  ucwords($assignment->status);
            $array[$key]['marks']            =  $assignment->marks;

            $studentAssignment = StudentAssignment::where([['assignment_id',$assignment->id],['user_id',$student_id]])->first();
            if($studentAssignment != null)
            {
                $file = [];
                foreach ($studentAssignment->AssignmentFilePath as $id => $attachments) 
                {
                    foreach ($attachments as $key1 => $value) 
                    {
                        if($key1 == 'path')
                        {
                            $file[$id] = $value;
                        }
                    }
                }
                $array[$key]['studentAssignmentId']     =  $studentAssignment->id;
                $array[$key]['studentAssignmentStatus'] =  $studentAssignment->status;
                $array[$key]['assignmentFile']          =  $file;
                $array[$key]['submittedOn']             =  date('d M Y',strtotime($studentAssignment->submitted_on));
                $array[$key]['obtainedMarks']           =  "$studentAssignment->obtained_marks";
                $array[$key]['teacherComments']         =  $studentAssignment->comments;
            }
            else
            {
                $array[$key]['studentAssignmentId']     =  null;
                $array[$key]['studentAssignmentStatus'] =  null;
                $array[$key]['assignmentFile']          =  null;
                $array[$key]['submittedOn']             =  null;
                $array[$key]['obtainedMarks']           =  null;
                $array[$key]['teacherComments']         =  null;
            }
        }
        
        return response()->json([
            'success'   =>  true,
            'message'   =>  'Ongoing Assignment',
            'data'      =>  $array == null ? []:$array,
            'meta' => [
                'current_page' => $assignments->currentPage(),
                'last_page' => $assignments->lastPage(),
                'per_page' => $assignments->perPage(),
                'total' => $assignments->total(),
                'next_page_url' => $assignments->nextPageUrl(),
                'prev_page_url' => $assignments->previousPageUrl(),
            ]
        ],200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function completed($student_id)
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $student = User::where('id',$student_id)->first();

        $query = Assignment::where([
                ['school_id',Auth::user()->school_id],
                ['academic_year_id',$academic_year->id],
                ['standardLink_id',$student->studentAcademicLatest->standardLink_id],
                ['submission_date','<=',date('Y-m-d')],
                ['status','completed']
            ]);
        // ->whereHas('assignmentApproval' , function($query) {
        //             $query->where('status','approved');
        //         })->get();
        //date filter  
        if (isset($request->date)) {
            $query->whereDate('assigned_date', $request->date);
        }

        //subject filter  
        if (isset($request->subject_id)) {
            $query->where('subject_id', $request->subject_id);
        }

        $assignments = $query->orderBy('submission_date','ASC')->paginate(10);

        $array = [];
        //$assignment = AssignmentResource::collection($assignment);
        foreach ($assignments as $key => $assignment) 
        {
            $array[$key]['id']               =  $assignment->id;
            $array[$key]['class']            =  $assignment->standardLink->StandardSection;
            $array[$key]['title']            =  $assignment->title;
            $array[$key]['description']      =  $assignment->description;
            $array[$key]['assignedDate']     =  $assignment->assigned_date=='' ? null:date('d-m-Y', strtotime($assignment->assigned_date));
            $array[$key]['submissionDate']   =  $assignment->submission_date=='' ? null:date('d-m-Y', strtotime($assignment->submission_date));
            $array[$key]['attachment']       =  $assignment->attachment == '' ? '':$assignment->AttachmentPath;
            $array[$key]['status']           =  ucwords($assignment->status);
            $array[$key]['marks']            =  $assignment->marks;

            $studentAssignment = StudentAssignment::where([['assignment_id',$assignment->id],['user_id',$student_id]])->first();
            if($studentAssignment != null)
            {
                $file = [];
                foreach ($studentAssignment->AssignmentFilePath as $id => $attachments) 
                {
                    foreach ($attachments as $key => $value) 
                    {
                        if($key == 'path')
                        {
                            $file[$id] = $value;
                        }
                    }
                }

                $array[$key]['studentAssignmentId']     =  $studentAssignment->id;
                $array[$key]['studentAssignmentStatus'] =  $studentAssignment->status;
                $array[$key]['assignmentFile']          =  $file;
                $array[$key]['submittedOn']             =  date('d M Y',strtotime($studentAssignment->submitted_on));
                $array[$key]['obtainedMarks']           =  $studentAssignment->obtained_marks;
                $array[$key]['teacherComments']         =  $studentAssignment->comments;
            }
            else
            {
                $array[$key]['studentAssignmentId']     =  null;
                $array[$key]['studentAssignmentStatus'] =  null;
                $array[$key]['assignmentFile']          =  null;
                $array[$key]['submittedOn']             =  null;
                $array[$key]['obtainedMarks']           =  null;
                $array[$key]['teacherComments']         =  null;
            }
        }
        
        return response()->json([
            'success'   =>  true,
            'message'   =>  'Completed Assignment',
            'data'      =>  $array == null ? []:$array,
            'meta' => [
                'current_page' => $assignments->currentPage(),
                'last_page' => $assignments->lastPage(),
                'per_page' => $assignments->perPage(),
                'total' => $assignments->total(),
                'next_page_url' => $assignments->nextPageUrl(),
                'prev_page_url' => $assignments->previousPageUrl(),
            ]
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($student_id,$id)
    {
        //
        $studentAssignment     =   StudentAssignment::where([['id',$id],['user_id',$student_id]])->first();
        $file = [];
        foreach ($studentAssignment->AttachmentPath as $id => $attachments) 
        {
            foreach ($attachments as $key => $value) 
            {
                if($key == 'path')
                {
                    $file[$id] = $value;
                }
            }
        }
        $assignment = [];

        $assignment['assignmentName']   =   $studentAssignment->assignment->title;
        $assignment['subject']          =   $studentAssignment->assignment->subject->name;
        $assignment['assignmentFile']   =   $file;
        $assignment['status']           =   $studentAssignment->status;
        if($studentAssignment->status == 'completed')
        {
            $assignment['obtainedMarks']    =   $studentAssignment->obtained_marks;
            $assignment['submittedOn']      =   date('d M Y', strtotime($studentAssignment->submitted_on));
            $assignment['comments']         =   $studentAssignment->comments;
        }
        elseif ($studentAssignment->status == 'submitted') 
        {
            $assignment['obtainedMarks']    =   null;
            $assignment['submittedOn']      =   null;
            $assignment['comments']         =   null;
        }
        
        return response()->json([
            'success'   =>  true,
            'message'   =>  'Assignment Mark',
            'data'      =>  $assignment == null ? []:$assignment
        ],200);
    }

    public function store(StudentAssignmentAddRequest $request,$assignment_id,$student_id)
    {
        //
        try
        {
            $school_id = Auth::user()->school_id;

            $studentAssignment = new StudentAssignment;

            $studentAssignment->assignment_id   =   $assignment_id;
            $studentAssignment->user_id         =   $student_id;
            $files[]   =   $request->file('assignment_file');
            
            $path = [];
            $i = 1;
            foreach($files as $file) 
            {
                $path[$i] = $this->uploadFile(Auth::user()->school->slug.'/assignments/student/'.$assignment_id,$file); 
                $i++;     
            }

            $studentAssignment->assignment_file =   $path;
            $studentAssignment->submitted_on    =   date('Y-m-d');
            $studentAssignment->status          =   'submitted';

            $studentAssignment->save();

            $assignment = Assignment::where('id',$assignment_id)->first();
            $teacher = User::where('id',$assignment->teacher_id)->first();
            $student = User::where('id',$studentAssignment->user_id)->first();

            $array=[];

            $array['school_id']  =   $school_id;
            $array['user_id']    =   $teacher->id;
            $array['message']    =   $student->FullName.' Added Assignment File';
            $array['type']       =   'assignment';

            event(new SinglePushEvent($array));

            $data = [];

            $data['user']       =   $teacher;
            $data['details']    =   trans('notification.student_assignment_add_msg',['student' => $student->FullName]);

            event(new SingleNotificationEvent($data));

            $message=trans('messages.add_success_msg',['module' => 'Assignment']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $studentAssignment,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_STUDENT_ASSIGNMENT,
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,$student_id)
    {
        //
        try
        {
            $studentassignment = StudentAssignment::where('id',$id)->first();

            if(Gate::allows('studentassignment',$studentassignment))
            {
                $assignment = Assignment::where('id',$studentassignment->assignment_id)->first();
                $student = User::where('id',$student_id)->first();
                $teacher = User::where('id',$assignment->teacher_id)->first();

                $studentassignment->status     =   'cancel';
                $studentassignment->save();

                $studentassignment->delete();

                $array = [];

                $array['school_id'] = $student->school_id;
                $array['user_id']   = $teacher->id;
                $array['message']   = $student->FullName.' Deleted Assignment File';
                $array['type']      = 'assignment';

                event(new SinglePushEvent($array));

                $data = [];

                $data['user']       =   $teacher;
                $data['details']    =   trans('notification.student_assignment_delete_msg',['student' => $student->FullName]);

                event(new SingleNotificationEvent($data));

                $message=trans('messages.delete_success_msg',['module' => 'Assignment File']);

                $ip= $this->getRequestIP();
                $this->doActivityLog(
                    $studentassignment,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                    LOGNAME_DELETE_STUDENT_ASSIGNMENT,
                    $message
                );

                return response()->json([
                    'success'   =>  true,
                    'message'   =>  $message,
                ],200);
            }
            else
            {
                abort(403);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            dd($e->getMessage());
        }
    }
}