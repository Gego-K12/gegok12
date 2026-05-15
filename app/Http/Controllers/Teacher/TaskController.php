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
use Illuminate\Support\Facades\DB;
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
        $tasks = $tasks->orderby('id','desc')->get(); 

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
    // public function changestatus(Request $request)
    // {
    //     try
    //     {
    //         if( $request->selectedTaskCount > 0 ) // if( count($request->selectedTaskCount) > 0 )
    //         {
    //             foreach ($request->task_completed as $task_id) 
    //             {
    //                 $task = Task::where('id',$task_id)->first();

    //                 $task->task_status = 1;

    //                 $task->save();

    //                 $message = trans('messages.task_check_success_msg');

    //                 $ip= $this->getRequestIP();
    //                 $this->doActivityLog(
    //                     $task,
    //                     Auth::user(),
    //                     ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
    //                     LOGNAME_MARK_TASK_COMPLETE,
    //                     $message
    //                 ); 
    //             }

    //             $res['success'] = $message;
    //             return $res;
    //         }
    //     }
    //     catch(Exception $e)
    //     {
    //         Log::info($e->getMessage());
    //         dd($e->getMessage());
    //     }   
    // }

    public function changeStatus(Request $request)
    {
        DB::beginTransaction();

        try {

            foreach ($request->task_completed as $id)
            {
                $assignee = TaskAssignee::findOrFail($id);

                $assignee->update([
                    'status' => 'completed',
                    // 'claimed_by' => Auth::id(),
                ]);

                // Check all assignees completed
                $pendingCount = TaskAssignee::where('task_id', $assignee->task_id)
                    ->where('status', 'pending')
                    ->count();

                if ($pendingCount == 0)
                {
                    Task::where('id', $assignee->task_id)
                        ->update([
                            'task_status' => 1
                        ]);
                }

                // Activity Log
                $message = trans('messages.task_check_success_msg');

                $ip = $this->getRequestIP();

                $this->doActivityLog(
                    $assignee,
                    Auth::user(),
                    [
                        'ip' => $ip,
                        'details' => request()->userAgent()
                    ],
                    LOGNAME_MARK_TASK_COMPLETE,
                    $message
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ], 500);
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
        $task = Task::where('id', $id)->firstOrFail();

        $task_assignees = TaskAssignee::with(['user', 'standardLink'])
            ->where('task_id', $id)
            ->get();

        $selectedUsers = [];
        $selectedTeachers = [];
        $selected_students = [];
        $selected_teachers = [];
        $standardLink_id = null;
        $class = null;
        $task_assignee_id = null;

        $assignees = [];

        foreach ($task_assignees as $task_assignee) {
            $task_assignee_id = $task_assignee->id;

            if ($task->type == 'teacher') {
                $selectedTeachers[] = $task_assignee->user_id;
            } elseif ($task->type == 'student') {
                $selectedUsers[] = $task_assignee->user_id;
                $standardLink_id = $task_assignee->standardLink_id;

                if ($task_assignee->standardLink) {
                    $class = $task_assignee->standardLink->StandardSection;
                }
            } elseif ($task->type == 'class') {
                $standardLink_id = $task_assignee->standardLink_id;

                if ($task_assignee->standardLink) {
                    $class = $task_assignee->standardLink->StandardSection;
                }
            }

            $name = '-';
            $username = '-';

            if ($task_assignee->user) {
                if (isset($task_assignee->user->FullName)) {
                    $name = $task_assignee->user->FullName;
                } elseif (isset($task_assignee->user->fullname)) {
                    $name = $task_assignee->user->fullname;
                } else {
                    $name = $task_assignee->user->name;
                }

                $username = $task_assignee->user->name;
            }

            $assignees[] = [
                'id'             => $task_assignee->id,
                'user_id'        => $task_assignee->user_id,
                'name'           => $name,
                'username'       => $username,
                'class'          => $task_assignee->standardLink ? $task_assignee->standardLink->StandardSection : null,
                'assigned_type'  => $task_assignee->assigned_type,
                'status'         => $task_assignee->status,
                'claimed_by'     => $task_assignee->claimed_by,
            ];
        }

        if ($task->type == 'student' && count($selectedUsers) > 0) {
            $students = User::whereIn('id', $selectedUsers)->get();
            $selected_students = UserResource::collection($students);
        }

        if ($task->type == 'teacher' && count($selectedTeachers) > 0) {
            $teachers = User::whereIn('id', $selectedTeachers)->get();
            $selected_teachers = TeacherResource::collection($teachers);
        }

        return response()->json([
            'task_id'           => $task->id,
            'task_assignee_id'  => $task_assignee_id,
            'title'             => $task->title,
            'to_do_list'        => $task->to_do_list,
            'task_date'         => $task->task_date ? date('d-m-Y H:i:s', strtotime($task->task_date)) : '-',
            'assignee_display'  => ucwords($task->type),
            'assignee'          => $task->type,
            'reminder_date'     => $task->ReminderValue ? date('d-m-Y H:i:s', strtotime($task->ReminderValue)) : '-',
            'selectedUsers'     => $selected_students,
            'standardLink_id'   => $standardLink_id,
            'class'             => $class,
            'teachers'          => $selected_teachers,
            'priority'          => $task->priority,
            'task_status'       => $task->task_status,
            'task_type'         => $task->task_type,
            'total_count'       => $task_assignees->count(),
            'completion_count'  => $task_assignees->where('status', 'completed')->count(),
            'assignees'         => $assignees,
        ]);
    }

    public function view($id)
    {
        return view('/teacher/todolist/show', [
            'taskId' => $id,
            'mode' => 'teacher',
        ]);
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