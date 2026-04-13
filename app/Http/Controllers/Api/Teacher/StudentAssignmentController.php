<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Api\Teacher;

use App\Http\Resources\API\Teacher\StudentAssignment as StudentAssignmentResource;
use App\Http\Requests\API\Teacher\StudentAssignmentUpdateRequest;
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
use Exception;
use Log;

class StudentAssignmentController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function submittedAssignmentList(Request $request,$assignment_id)
    {
        //
        $studentAssignment = StudentAssignment::where([['assignment_id',$assignment_id],['status','submitted']])->paginate(10);
        $list = StudentAssignmentResource::collection($studentAssignment);

        return response()->json([
            'success' => true,
            'message' => 'Submitted Assignment List',
            'data' => $list,
            'meta' => [
                'current_page' => $studentAssignment->currentPage(),
                'last_page' => $studentAssignment->lastPage(),
                'per_page' => $studentAssignment->perPage(),
                'total' => $studentAssignment->total(),
            ]
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function completedAssignmentList(Request $request,$assignment_id)
    {
        //
        $studentAssignment = StudentAssignment::where([['assignment_id',$assignment_id],['status','completed']])->paginate(10);
        $list = StudentAssignmentResource::collection($studentAssignment);

        return response()->json([
            'success'   =>  true,
            'message'   =>  'Completed Assignment List',
            'data'      =>  $list,
            'meta' => [
                'current_page' => $studentAssignment->currentPage(),
                'last_page' => $studentAssignment->lastPage(),
                'per_page' => $studentAssignment->perPage(),
                'total' => $studentAssignment->total(),
            ]
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentAssignmentUpdateRequest $request,$id)
    {
        //
        try
        {
            $school_id = Auth::user()->school_id;
            $studentAssignment     =   StudentAssignment::where('id',$id)->first();

            $studentAssignment->obtained_marks  =   $request->obtained_marks;
            $studentAssignment->comments        =   $request->comments;
            $studentAssignment->marks_given_by  =   Auth::id();
            $studentAssignment->marks_given_on  =   date('Y-m-d');
            $studentAssignment->status          =   'completed';

            $studentAssignment->save();

            foreach ($studentAssignment->student->parents as $parent) 
            {
                $array=[];

                $array['school_id']  =  $school_id;
                $array['user_id']    =  $parent->userParent->id;
                $array['message']    = 'Assignment Marks Updated';
                $array['type']       = 'assignment';
                                
                event(new SinglePushEvent($array));
            }

            $message=trans('messages.add_success_msg',['module' => 'Assignment Mark']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $studentAssignment,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_UPDATE_STUDENT_ASSIGNMENT,
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
        $studentAssignment     =   StudentAssignment::where('id',$id)->first();
        $array = [];

        $array['obtained_marks']    =   $studentAssignment->obtained_marks;
        $array['comments']          =   $studentAssignment->comments;

        return response()->json([
            'success'   =>  true,
            'message'   =>  'Show Mark',
            'data'      =>  $array
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StudentAssignmentUpdateRequest $request, $id)
    {
        //
        try
        {
            $school_id = Auth::user()->school_id;
            $studentAssignment     =   StudentAssignment::where('id',$id)->first();

            $studentAssignment->obtained_marks  =   $request->obtained_marks;
            $studentAssignment->comments        =   $request->comments;
            $studentAssignment->marks_given_by  =   Auth::id();
            $studentAssignment->marks_given_on  =   date('Y-m-d');
            $studentAssignment->status          =   'completed';

            $studentAssignment->save();

            foreach ($studentAssignment->student->parents as $parent) 
            {
                $array=[];

                $array['school_id']  =  $school_id;
                $array['user_id']    =  $parent->userParent->id;
                $array['message']    = 'Assignment Marks Updated';
                $array['type']       = 'assignment';
                                
                event(new SinglePushEvent($array));
            }

            $message=trans('messages.update_success_msg',['module' => 'Assignment Mark']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $studentAssignment,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_UPDATE_STUDENT_ASSIGNMENT,
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
            //dd($e->getMessage());
        }
    }
}