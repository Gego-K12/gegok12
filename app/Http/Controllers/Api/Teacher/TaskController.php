<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api\Teacher;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\TaskStatusUpdateRequest;
use App\Http\Requests\API\Teacher\TaskRequest;
use App\Http\Resources\API\Teacher\Task as TaskResource;
use App\Http\Resources\Teacher\Studentlist as StudentlistResource;
use App\Http\Resources\Teacher\Teacher as TeacherResource;
use App\Http\Resources\User as UserResource;
use App\Models\Group;
use App\Models\Task;
use App\Models\TaskAssignee;
use App\Models\User;
use App\Services\TaskReaderService;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\TodolistProcess;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;

class TaskController extends Controller
{
    use Common;
    use LogActivity;
    use TodolistProcess;

    public function __construct(protected TaskReaderService $taskReader) {}

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function myActiveList()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $tasks = Task::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
        ])->ByType('by_me', Auth::id())->ByStatus(0)->get();

        $tasks = TaskResource::collection($tasks)->groupby('Flag');

        // if($tasks['Today']!=null)
        // {
        //     $tasks['Today'] = [];
        // }
        // if($tasks['Overdue']!=null)
        // {
        //     $tasks['Overdue'] = [];
        // }
        // if( $tasks['Upcoming']!=null )
        // {
        //     $tasks['Upcoming'] = [];
        // }

        if (! isset($tasks['Today'])) {
            $tasks['Today'] = [];
        }
        if (! isset($tasks['Overdue'])) {
            $tasks['Overdue'] = [];
        }
        if (! isset($tasks['Upcoming'])) {
            $tasks['Upcoming'] = [];
        }

        /*return response()->json([
            'success'   =>  true,
            'message'   =>  'My Active Task List',
            'data'      =>  $tasks
        ],200);*/
        return $tasks;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function myCompletedList()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $tasks = Task::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
        ])->ByType('by_me', Auth::id())->ByStatus(1)->get();

        $tasks = TaskResource::collection($tasks)->groupby('Flag');

        //   if($tasks['Today']!=null)
        // {
        //     $tasks['Today'] = [];
        // }
        // if($tasks['Overdue']!=null)
        // {
        //     $tasks['Overdue'] = [];
        // }
        // if( $tasks['Upcoming']!=null )
        // {
        //     $tasks['Upcoming'] = [];
        // }

        // add new
        if (! isset($tasks['Today'])) {
            $tasks['Today'] = [];
        }
        if (! isset($tasks['Overdue'])) {
            $tasks['Overdue'] = [];
        }
        if (! isset($tasks['Upcoming'])) {
            $tasks['Upcoming'] = [];
        }

        /*return response()->json([
            'success'   =>  true,
            'message'   =>  'My Completed Task List',
            'data'      =>  $tasks
        ],200);*/
        return $tasks;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function activeList()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $tasks = Task::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
        ])->ByType('to_me', Auth::id())->ByStatus(0)->get();

        $tasks = TaskResource::collection($tasks)->groupby('Flag');
        // if( count($tasks['Today']) == 0 )
        // {
        //     $tasks['Today'] = [];
        // }
        // if( count($tasks['Overdue']) == 0 )
        // {
        //     $tasks['Overdue'] = [];
        // }
        // if( count($tasks['Upcoming']) == 0 )
        // {
        //     $tasks['Upcoming'] = [];
        // }

        // new
        if (count($tasks['Today'] ?? []) == 0) {
            $tasks['Today'] = [];
        }
        if (count($tasks['Overdue'] ?? []) == 0) {
            $tasks['Overdue'] = [];
        }
        if (count($tasks['Upcoming'] ?? []) == 0) {
            $tasks['Upcoming'] = [];
        }

        /*return response()->json([
            'success'   =>  true,
            'message'   =>  'Active Task List',
            'data'      =>  $tasks
        ],200);*/
        return $tasks;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function completedList()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $tasks = Task::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
        ])->ByType('to_me', Auth::id())->ByStatus(1)->get();

        $tasks = TaskResource::collection($tasks)->groupby('Flag');
        // if( count($tasks['Today']) == 0 )
        // {
        //     $tasks['Today'] = [];
        // }
        // if( count($tasks['Overdue']) == 0 )
        // {
        //     $tasks['Overdue'] = [];
        // }
        // if( count($tasks['Upcoming']) == 0 )
        // {
        //     $tasks['Upcoming'] = [];
        // }

        // new
        if (count($tasks['Today'] ?? []) == 0) {
            $tasks['Today'] = [];
        }
        if (count($tasks['Overdue'] ?? []) == 0) {
            $tasks['Overdue'] = [];
        }
        if (count($tasks['Upcoming'] ?? []) == 0) {
            $tasks['Upcoming'] = [];
        }

        /*return response()->json([
            'success'   =>  true,
            'message'   =>  'Completed Task List',
            'data'      =>  $tasks
        ],200);*/
        return $tasks;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $standardlink_subject_list = SiteHelper::getStandardSubjectList(
            Auth::user()->school_id,
            Auth::id()
        );

        $standardlinks = collect($standardlink_subject_list['standardLinklist']->toArray($request))
            ->map(function ($item) {

                $item['groups'] = Group::where('standardLink_id', $item['id'])
                    ->select('id', 'group_name')
                    ->get()
                    ->toArray();

                return $item;
            });

        $array = [];

        $array['task_assignee_list'] = SiteHelper::getTaskAssigneeList();
        $array['task_reminder_list'] = SiteHelper::getTaskReminderList();
        $array['standardlinks'] = $standardlinks->values();

        return response()->json($array, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function teacherList()
    {
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $teachers = SiteHelper::getDoToTeachers(Auth::user()->school_id, $academic_year->id);
        $teachers = TeacherResource::collection($teachers);

        return response()->json([
            'success' => true,
            'message' => 'Add Task Teacher List',
            'data' => $teachers,
        ], 200);
    }

    public function nonTeacherList()
    {
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $teachers = SiteHelper::getNonTeachers(Auth::user()->school_id, $academic_year->id);
        $teachers = TeacherResource::collection($teachers);

        return response()->json([
            'success' => true,
            'message' => 'Add Task Non-Teacher List',
            'data' => $teachers,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function studentList($standardlink_id)
    {
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $students = SiteHelper::getClassStudents(Auth::user()->school_id, $academic_year->id, $standardlink_id);
        $students = StudentlistResource::collection($students);

        return response()->json([
            'success' => true,
            'message' => 'Add Task Student List',
            'data' => $students,
        ], 200);
    }

    // public function changestatus(TaskStatusUpdateRequest $request)
    // {
    //     try
    //     {
    //         if( count($request->task_completed) > 0 )
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

    //             return response()->json([
    //                 'success'   =>  true,
    //                 'message'   =>  $message,
    //             ],200);
    //         }
    //     }
    //     catch(Exception $e)
    //     {
    //         Log::info($e->getMessage());
    //     }
    // }

    public function changeStatus(TaskStatusUpdateRequest $request)
    {
        DB::beginTransaction();

        try {

            foreach ($request->task_completed as $id) {
                // $id is a Task id (validated via exists:task,id in
                // TaskStatusUpdateRequest) - look up this teacher's own
                // assignee row for that task, not a TaskAssignee id
                // directly. The previous TaskAssignee::findOrFail($id)
                // looked $id up as a task_assignees primary key - a
                // different, independently auto-incrementing table - with
                // no ownership check at all, letting any authenticated
                // teacher complete (or corrupt the status of) any other
                // user's task assignment by guessing/incrementing ids.
                $assignee = TaskAssignee::where([
                    ['task_id', $id],
                    ['user_id', Auth::id()],
                ])->first();

                if (! $assignee) {
                    throw new Exception('Task assignment not found for this teacher.');
                }

                $assignee->update([
                    'status' => 'completed',
                    // 'claimed_by' => Auth::id(),
                ]);

                // Check all assignees completed
                $pendingCount = TaskAssignee::where('task_id', $assignee->task_id)
                    ->where('status', 'pending')
                    ->count();

                if ($pendingCount == 0) {
                    Task::where('id', $assignee->task_id)
                        ->update([
                            'task_status' => 1,
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
                        'details' => request()->userAgent(),
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

        } catch (Exception $e) {

            DB::rollBack();

            Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(TaskRequest $request)
    {
        //
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $auth_id = Auth::id();

            $task = $this->addTaskAssignee($request, $school_id, $academic_year->id, $auth_id);

            $message = trans('messages.add_success_msg', ['module' => 'Task']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $task,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_TASK,
                $message
            );

            return response()->json([
                'success' => true,
                'message' => $message,
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $task = $this->taskReader->find($id, Auth::user()->school_id);

        if (! $task) {
            abort(404);
        }

        $task_assignees = TaskAssignee::where('task_id', $id)->get();

        foreach ($task_assignees as $key => $task_assignee) {
            if ($task->type == 'teacher') {
                $selected_teachers[$key] = $task_assignee->user_id;
            } elseif ($task->type == 'student') {
                $selectedUsers[$key] = $task_assignee->user_id;
                $standardLink_id = $task_assignee->standardLink_id;
                $class = $task_assignee->standardLink->StandardSection;
            } elseif ($task->type == 'class') {
                $class = $task_assignee->standardLink->StandardSection;
            }
        }
        $array = [];

        if ($task->type == 'student') {
            $selected_students = User::whereIn('id', $selectedUsers)->get();
            $selected_students = UserResource::collection($selected_students);
        }
        if ($task->type == 'teacher') {
            $selected_teachers = User::whereIn('id', $selected_teachers)->get();
            $selected_teachers = TeacherResource::collection($selected_teachers);
        }
        $array['task_id'] = $task->id;
        $array['title'] = $task->title;
        $array['to_do_list'] = $task->to_do_list;
        $array['task_date'] = date('d-m-Y H:i:s', strtotime($task->task_date));
        $array['assignee_display'] = ucwords($task->type);
        $array['assignee'] = $task->type;
        $array['reminder_date'] = date('d-m-Y H:i:s', strtotime($task->ReminderValue));
        $array['selectedUsers'] = $selected_students;
        $array['standardLink_id'] = $standardLink_id;
        $array['class'] = $class;
        $array['teachers'] = $selected_teachers;

        return response()->json([
            'success' => true,
            'message' => 'Show Task',
            'data' => $array,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $task = $this->taskReader->find($id, Auth::user()->school_id);

        if (! $task) {
            abort(404);
        }

        $task_assignees = TaskAssignee::where('task_id', $id)->get();

        foreach ($task_assignees as $key => $task_assignee) {
            if ($task->type == 'teacher') {
                $selected_teachers[$key] = $task_assignee->user_id;
            } elseif ($task->type == 'student') {
                $selectedUsers[$key] = $task_assignee->user_id;
                $standardLink_id = $task_assignee->standardLink_id;
            } elseif ($task->type == 'class') {
                $standardLink_id = $task_assignee->standardLink_id;
            }
        }
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $teachers = SiteHelper::getTeachers(Auth::user()->school_id, $academic_year->id);
        $array = [];

        if ($task->type == 'teacher') {
            $selected_teachers = User::whereIn('id', $selected_teachers)->get();
            $selected_teachers = TeacherResource::collection($selected_teachers);
        }
        $array['task_id'] = $task->id;
        $array['task_assignee_id'] = $task_assignee->id;
        $array['title'] = $task->title;
        $array['to_do_list'] = $task->to_do_list;
        $array['task_date'] = date('Y-m-d H:i:s', strtotime($task->task_date));
        $array['assignee'] = $task->type;
        $array['reminder_date'] = $task->ReminderValue;
        $array['selectedUsers'] = $selectedUsers;
        $array['standardLink_id'] = $standardLink_id;
        $array['teachers'] = $selected_teachers;

        return response()->json([
            'success' => true,
            'message' => 'Edit Task',
            'data' => $array,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(TaskRequest $request, $id)
    {
        $school_id = Auth::user()->school_id;

        if (! $this->taskReader->find($id, $school_id)) {
            abort(404);
        }

        try {
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $auth_id = Auth::id();

            $task = $this->editTaskAssignee($request, $auth_id, $id, $school_id);

            $message = trans('messages.update_success_msg', ['module' => 'Task']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $task,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_TASK,
                $message
            );

            return response()->json([
                'success' => true,
                'message' => $message,
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function snooze(Request $request, $id)
    {
        $school_id = Auth::user()->school_id;
        $task = $this->taskReader->find($id, $school_id);

        if (! $task) {
            abort(404);
        }

        try {
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $auth_id = Auth::id();
            if ($task->snooze == 0) {
                $task = $this->snoozeTask($request, $auth_id, $id, $school_id);

                $mins = env('SNOOZE_TIME') / 60;
                $message = trans('messages.task_snooze_msg', ['mins' => $mins]);
            } else {
                $message = trans('messages.task_snooze_exists_msg');
            }

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $task,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_SNOOZE_TASK,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $task = $this->taskReader->find($id, Auth::user()->school_id);

        if (! $task) {
            abort(404);
        }

        try {
            $task_assignees = TaskAssignee::where('task_id', $task->id)->get();
            foreach ($task_assignees as $task_assignee) {
                $task_assignee->delete();
            }

            $task->delete();

            $message = trans('messages.delete_success_msg', ['module' => 'Task']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $task,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_TASK,
                $message
            );

            return response()->json([
                'success' => true,
                'message' => $message,
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
