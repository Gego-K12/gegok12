<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Teacher;

use App\Http\Resources\Teacher\Studentlist as StudentlistResource;
use App\Http\Resources\Teacher\Teacher as TeacherResource;
use App\Http\Resources\Teacher\Task as TaskResource;
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

/**
 * Class TaskController
 *
 * Handles teacher task (To-Do list) operations such as
 * listing, creating, updating, snoozing, completing and deleting tasks.
 *
 * @package App\Http\Controllers\Teacher
 */
class TaskController extends Controller
{
    use TodolistProcess;
    use LogActivity;
    use Common;

    /**
     * Display task list grouped by task flag (API).
     *
     * @param  Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function showlist(Request $request)
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $tasks = Task::where([
                ['school_id',$school_id],
                ['academic_year_id',$academic_year->id]
            ])->ByType($request->type,Auth::id())->ByStatus($request->status);
        if(count((array)\Request::getQueryString())>0)
        {
            if($request->search != '')
            { 
                $tasks = $tasks->where('title','LIKE','%'.$request->search.'%')->orWhere('to_do_list','LIKE','%'.$request->search.'%');
            }
        }
        $tasks = $tasks->get(); 

        $tasks = TaskResource::collection($tasks)->groupby('task_flag');
        
        return $tasks;
    }

    /**
     * Get required data for task create form.
     *
     * Includes standards, students, teachers and default task date.
     *
     * @param  Request  $request
     * @return array
     */
    public function list(Request $request)
    {
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $teachers = SiteHelper::getDoToTeachers(Auth::user()->school_id,$academic_year->id);
        $non_teachers = SiteHelper::getNonTeachers(Auth::user()->school_id,$academic_year->id);
        $array = [];

        $standardlink_subject_list = SiteHelper::getStandardSubjectList(Auth::user()->school_id,Auth::id());
        $array['standardlinks'] = $standardlink_subject_list['standardLinklist'];

        if($request->standardlink_id != null)
        {
            $students = SiteHelper::getClassStudents(
                Auth::user()->school_id,
                $academic_year->id,
                $request->standardlink_id
            );

            $array['students'] = StudentlistResource::collection($students);
        }
        else
        {
            $array['students'] = [];
        }

        $array['teachers']  = TeacherResource::collection($teachers);
        $array['nonteachers']  = TeacherResource::collection($non_teachers);
        $array['task_date'] = date('Y-m-d');

        return response()->json($array);
    }
    /**
     * Mark selected tasks as completed.
     *
     * @param  Request  $request
     * @return array|null
     */
    public function changestatus(Request $request)
    {
        try
        {
            if( $request->selectedTaskCount > 0 ) // if( count($request->selectedTaskCount) > 0 )
            {
                foreach ($request->task_completed as $task_id) 
                {
                    $task = Task::where('id',$task_id)->first();

                    $task->task_status = 1;

                    $task->save();

                    $message = trans('messages.task_check_success_msg');

                    $ip= $this->getRequestIP();
                    $this->doActivityLog(
                        $task,
                        Auth::user(),
                        ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                        LOGNAME_MARK_TASK_COMPLETE,
                        $message
                    ); 
                }

                $res['success'] = $message;
                return $res;
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            dd($e->getMessage());
        }   
    }
    
    /**
     * Display task list page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    { 
        $query = \Request::getQueryString();
        return view('/teacher/todolist/index',['query' => $query]);
    }
    
    /**
     * Show task creation page.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    { 
        $query = \Request::getQueryString();
        return view('/teacher/todolist/create',['query' => $query]);
    }
    
    /**
     * Store a newly created task.
     *
     * @param  TaskRequest  $request
     * @return array
     */
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
     * Show task details for viewing/editing (API).
     *
     * @param  int  $id
     * @return array
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
     * Get task data for edit form (API).
     *
     * @param  Request  $request
     * @param  int  $id
     * @return array
     */
    public function editList(Request $request,$id)
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
            }
            elseif ($task->type == 'class') 
            {
                $array['standardLink_id'] = $task_assignee->standardLink_id;
            }
        }
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $teachers = SiteHelper::getTeachers(Auth::user()->school_id,$academic_year->id);
        $array = [];

        $array['standardlinks'] = SiteHelper::getStandardLinkList(Auth::user()->school_id);
        if($request->standardLink_id != null)
        {
            $students = SiteHelper::getClassStudents(Auth::user()->school_id,$academic_year->id,$request->standardLink_id);
            $array['students'] = StudentlistResource::collection($students);
            $array['standardLink_id'] = $request->standardLink_id;
        }
        else
        {
            if($task->type == 'student')
            {
                $students = SiteHelper::getClassStudents(Auth::user()->school_id,$academic_year->id,$task_assignees[0]['standardLink_id']);
                $array['students'] = StudentlistResource::collection($students);
                $array['standardLink_id'] = $task_assignees[0]['standardLink_id'];
            }
        }

        $array['teacherlist']       = TeacherResource::collection($teachers);
        $array['task_id']           =  $task->id;
        $array['task_assignee_id']  =  $task_assignee->id;
        $array['title']             =  $task->title;
        $array['to_do_list']        =  $task->to_do_list;
        $array['task_date']         =  date('d-m-Y H:i:s',strtotime($task->task_date));
        $array['assignee']          =  $task->type;
        $array['reminder']          =  $task->reminder;
        $array['reminder_date']     =  $task->ReminderValue;
        $array['selectedUsers']     =  $selectedUsers;
        $array['teachers']          =  $selected_teachers;
    
        return $array;
    }

     /**
     * Show task edit page.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        //
        $task = Task::where('id',$id)->first(); 
        return view('/teacher/todolist/edit' , ['task' => $task]);
    }

    /**
     * Update an existing task.
     *
     * @param  TaskRequest  $request
     * @param  int  $id
     * @return array
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
     * Snooze a task for configured duration.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return array
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
     * Delete a task.
     *
     * @param  int  $id
     * @return array
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
            // dd($e->getMessage());
        }
    }
}