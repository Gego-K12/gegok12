<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Student;

use App\Http\Resources\Student\Assignment as AssignmentResource;
use App\Events\Notification\SingleNotificationEvent;
use App\Http\Requests\StudentAssignmentAddRequest;
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
    public function showList(Request $request)
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $assignment = Assignment::where([
                ['school_id',Auth::user()->school_id],
                ['academic_year_id',$academic_year->id],
                ['standardLink_id',Auth::user()->studentAcademicLatest->standardLink_id],
                ['submission_date','>=',date('Y-m-d')],
                ['status','ongoing']
            ])->whereHas('assignmentApproval' , function($query) {
                    $query->where('status','approved');
                });
        if(count((array)\Request::getQueryString())>0)
        {
            if($request->showCompleted == 'true')
            { 
                $assignment = $assignment->orWhere([
                    ['school_id',Auth::user()->school_id],
                    ['academic_year_id',$academic_year->id],
                    ['standardLink_id',Auth::user()->studentAcademicLatest->standardLink_id],
                    ['submission_date','<=',date('Y-m-d')],
                    ['status','ongoing']
                ])->whereHas('assignmentApproval' , function($query) {
                    $query->where('status','approved');
                });
            }

            if($request->search != null)
            {
                $assignment = $assignment->where('title','LIKE','%'.$request->search.'%')->orWhere('description','LIKE','%'.$request->search.'%')->orWhere('marks','LIKE','%'.$request->search.'%')->orWhereHas('subject' , function ($query) use($request)
                {
                    $query->where('name','LIKE','%'.$request->search.'%');
                });
            }
        }
        $assignment=$assignment->orderBy('id','desc')->paginate(10);
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

        $standardLink_id = Auth()->user()->studentAcademicLatest->standardLink_id;

        return view('/student/assignment/index' , ['query' => $query , 'standardLink_id' => $standardLink_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            // $request->validate([
            //     'assignment_id' => 'required|exists:assignments,id',
            //     'assignment_file' => 'required',
            //     'assignment_file.*' => 'image|mimes:jpg,jpeg,png|max:8192'
            // ]);

            $school_id = Auth::user()->school_id;

            $studentAssignment = new StudentAssignment;
            $studentAssignment->assignment_id = $request->assignment_id;
            $studentAssignment->user_id = Auth::id();

            $uploadedPaths = [];

            if ($request->hasFile('assignment_file')) {

                $files = $request->file('assignment_file');

                // If single file, convert to array safely
                if (!is_array($files)) {
                    $files = [$files];
                }

                foreach ($files as $file) {

                    if ($file instanceof \Illuminate\Http\UploadedFile) {

                        $path = $this->uploadFile(
                            Auth::user()->school->slug . '/assignments/student/' . $request->assignment_id,
                            $file
                        );

                        $uploadedPaths[] = $path;
                    }
                }
            }

            // $studentAssignment->assignment_file = json_encode($uploadedPaths);
            $studentAssignment->assignment_file = $uploadedPaths;
            $studentAssignment->submitted_on = now();
            $studentAssignment->status = 'submitted';
            $studentAssignment->save();

            $assignment = Assignment::findOrFail($request->assignment_id);
            $teacher = User::findOrFail($assignment->teacher_id);
            $student = Auth::user();

            event(new SinglePushEvent([
                'school_id' => $school_id,
                'user_id'   => $teacher->id,
                'message'   => $student->FullName . ' Added Assignment File',
                'type'      => 'assignment'
            ]));

            event(new SingleNotificationEvent([
                'user'    => $teacher,
                'details' => trans('notification.student_assignment_add_msg')
            ]));

            $message = trans('messages.add_success_msg', ['module' => 'Assignment']);

            $this->doActivityLog(
                $studentAssignment,
                $student,
                [
                    'ip' => $request->ip(),
                    'details' => $request->userAgent()
                ],
                LOGNAME_ADD_STUDENT_ASSIGNMENT,
                $message
            );

            return response()->json([
                'status' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {

            Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Upload failed'
            ], 500);
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
        $studentAssignment     =   StudentAssignment::where('id',$id)->first();
        $array = [];

        $array['assignment_file']   =   $studentAssignment->AssignmentFilePath;
        $array['obtained_marks']    =   $studentAssignment->obtained_marks;
        $array['comments']          =   $studentAssignment->comments;
        
        return $array;
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
            $studentAssignment = StudentAssignment::where('id',$id)->first();

            if(Gate::allows('studentassignment',$studentAssignment))
            {
                $assignment = Assignment::where('id',$studentAssignment->assignment_id)->first();
                $student = User::where('id',$studentAssignment->user_id)->first();
                $teacher = User::where('id',$assignment->teacher_id)->first();

                $studentAssignment->status     =   'cancel';
                $studentAssignment->save();

                $studentAssignment->delete();

                $data = [];

                $data['user']       =   $teacher;
                $data['details']    =   trans('notification.student_assignment_delete_msg',['student' => $student->FullName]);

                event(new SingleNotificationEvent($data));

                $message=trans('messages.delete_success_msg',['module' => 'Assignment File']);

                $ip= $this->getRequestIP();
                $this->doActivityLog(
                    $studentAssignment,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                    LOGNAME_DELETE_STUDENT_ASSIGNMENT,
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