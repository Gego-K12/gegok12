<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Receptionist;

use App\Http\Resources\Receptionist\Task as TaskResource;
use App\Http\Resources\Teacher as TeacherResource;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Traits\TodolistProcess;
use App\Models\TaskAssignee;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Traits\Common;
use App\Models\Task;
use App\Models\User;
use Exception;
use Log;

class TaskController extends Controller
{
    use TodolistProcess;
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showlist(Request $request)
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $tasks = Task::where([
                ['school_id',$school_id],
                ['academic_year_id',$academic_year->id]
            ])->ByType($request->type,Auth::id())->ByStatus($request->status)->get();

        $tasks = TaskResource::collection($tasks)->groupby('task_flag');
        
        return $tasks;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        // $array = [];

        // $array['task_date'] = date('Y-m-d');
        
        // return $array;
        $tasks = []; 

        return response()->json($tasks);
    }

    public function changestatus(Request $request)
    {
        try
        {
            $task = $this->updatestatus($request);
            // if($request->selectedTaskCount > 0 )
            // {
            //     foreach ($request->task_completed as $task_id) 
            //     {
            //         $task = Task::where('id',$task_id)->first();

            //         $task->task_status = 1;

            //         $task->save();

            //         $message = trans('messages.task_check_success_msg');

            //         $ip= $this->getRequestIP();
            //         $this->doActivityLog(
            //             $task,
            //             Auth::user(),
            //             ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
            //             LOGNAME_MARK_TASK_COMPLETE,
            //             $message
            //         ); 
            //     }

                // $res['success'] = $message;
                return $task;
            // }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            dd($e->getMessage());
        }   
    }

    public function index()
    { 
        $query = \Request::getQueryString();
        return view('/reception/todolist/index',['query' => $query]);
    }

    public function create()
    { 
        $query = \Request::getQueryString();
        return view('/reception/todolist/create',['query' => $query]);
    }

    public function store(TaskRequest $request)
    {
        try 
        {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $auth_id = Auth::id();

            $task = $this->addTaskAssignee( $request , $school_id , $academic_year->id , $auth_id );

            $message = trans('messages.add_success_msg',['module' => 'Task']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $task,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_TASK,
                $message
            ); 

            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            dd($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $task = Task::where('id',$id)->first(); 
        $task_assignees = TaskAssignee::where('task_id',$id)->get();
        
        foreach ($task_assignees as $key => $task_assignee) 
        {
            if($task->type == 'teacher')
            {
                $selected_teachers[$key] = $task_assignee->user_id;
            }
            elseif($task->type == 'student')
            {
                $selectedUsers[$key] = $task_assignee->user_id;
                $standardLink_id = $task_assignee->standardLink_id;
                $class = $task_assignee->standardLink->StandardSection;
            }
            elseif ($task->type == 'class') 
            {
                $class = $task_assignee->standardLink->StandardSection;
            }
        }
        $array = [];

        if($task->type == 'student')
        {
            $selected_students = User::whereIn('id',$selectedUsers)->get();
            $selected_students = UserResource::collection($selected_students);
        }
        if($task->type == 'teacher')
        {
            $selected_teachers  = User::whereIn('id',$selected_teachers)->get();
            $selected_teachers  = TeacherResource::collection($selected_teachers);
        }
        $array['task_id']           =  $task->id;
        $array['task_assignee_id']  =  $task_assignee->id;
        $array['title']             =  $task->title;
        $array['to_do_list']        =  $task->to_do_list;
        $array['task_date']         =  date('d-m-Y H:i:s',strtotime($task->task_date));
        $array['assignee_display']  =  ucwords($task->type);
        $array['assignee']          =  $task->type;
        $array['reminder_date']     =  date('d-m-Y H:i:s',strtotime($task->ReminderValue));
        $array['selectedUsers']     =  $selected_students;
        $array['standardLink_id']   =  $standardLink_id;
        $array['class']             =  $class;
        $array['teachers']          =  $selected_teachers;
    
        return $array;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editList(Request $request,$id)
    {
        //
        $task = Task::where('id',$id)->first(); 
        $task_assignees = TaskAssignee::where('task_id',$id)->get();
        
        $array = [];

        $array['task_date']         =  date('Y-m-d'); 
        $array['task_id']           =  $task->id;
        $array['task_assignee_id']  =  $task_assignee->id;
        $array['title']             =  $task->title;
        $array['to_do_list']        =  $task->to_do_list;
        $array['task_date']         =  date('d-m-Y H:i:s',strtotime($task->task_date));
        $array['assignee']          =  $task->type;
        $array['reminder']          =  $task->reminder;
    
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
        $task = Task::where('id',$id)->first(); 
        return view('/reception/todolist/edit' , ['task' => $task]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TaskRequest $request, $id)
    {
        try
        {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $auth_id = Auth::id();

            $task = $this->editTaskAssignee( $request , $auth_id , $id);

            $message=trans('messages.update_success_msg',['module' => 'Task']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $task,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_TASK,
                $message
            );

            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            dd($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function snooze(Request $request, $id)
    {
        try
        {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $auth_id = Auth::id();
            $task = Task::where('id',$id)->first();
            if($task->snooze == 0)
            {
                $task = $this->snoozeTask( $request , $auth_id , $id);

                $mins = env('SNOOZE_TIME') / 60;
                $message=trans('messages.task_snooze_msg',['mins' => $mins]);
            }
            else
            {
                $message=trans('messages.task_snooze_exists_msg');
            }

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $task,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_SNOOZE_TASK,
                $message
            );

            $res['success'] = $message;
            return $res;
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
        try 
        {
            $task = Task::where('id',$id)->first();
            
            $task->delete();       

            $message=trans('messages.delete_success_msg',['module' => 'Task']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $task,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_TASK,
                $message
            );

            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e) 
        {
            Log::info($e->getMessage());
            dd($e->getMessage());
        }
    }
}