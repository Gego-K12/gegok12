<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\TaskRequest;
use App\Http\Resources\API\Task as TaskResource;
use App\Models\Task;
use App\Models\TaskAssignee;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\TodolistProcess;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Log;

class TaskController extends Controller
{
    use Common;
    use LogActivity;
    use TodolistProcess;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function myActiveList($student_id)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $tasks = Task::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
        ])->ByType('by_me', $student_id)->ByStatus(0)->get();

        $tasks = TaskResource::collection($tasks)->groupby('Flag');
        if (count($tasks['Today']) == 0) {
            $tasks['Today'] = [];
        }
        if (count($tasks['Overdue']) == 0) {
            $tasks['Overdue'] = [];
        }
        if (count($tasks['Upcoming']) == 0) {
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
    public function myCompletedList($student_id)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $tasks = Task::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
        ])->ByType('by_me', $student_id)->ByStatus(1)->get();

        $tasks = TaskResource::collection($tasks)->groupby('Flag');
        if (count($tasks['Today']) == 0) {
            $tasks['Today'] = [];
        }
        if (count($tasks['Overdue']) == 0) {
            $tasks['Overdue'] = [];
        }
        if (count($tasks['Upcoming']) == 0) {
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
    public function activeList($student_id)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $tasks = Task::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
        ])->ByType('to_me', $student_id)->ByStatus(0)->get();

        $tasks = TaskResource::collection($tasks)->groupby('Flag');
        if (count($tasks['Today']) == 0) {
            $tasks['Today'] = [];
        }
        if (count($tasks['Overdue']) == 0) {
            $tasks['Overdue'] = [];
        }
        if (count($tasks['Upcoming']) == 0) {
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
    public function completedList($student_id)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $tasks = Task::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
        ])->ByType('to_me', $student_id)->ByStatus(1)->get();

        $tasks = TaskResource::collection($tasks)->groupby('Flag');
        if (count($tasks['Today']) == 0) {
            $tasks['Today'] = [];
        }
        if (count($tasks['Overdue']) == 0) {
            $tasks['Overdue'] = [];
        }
        if (count($tasks['Upcoming']) == 0) {
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
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        $task_reminder_list = SiteHelper::getTaskReminderList();

        return response()->json([
            'success' => true,
            'message' => 'Add Task List',
            'data' => $task_reminder_list,
        ], 200);
    }

    public function changestatus(Request $request)
    {
        try {
            if (count($request->task_completed) > 0) {
                foreach ($request->task_completed as $task_id) {
                    $task = Task::where('id', $task_id)->first();

                    $task->task_status = 1;

                    $task->save();

                    $message = trans('messages.task_check_success_msg');

                    $ip = $this->getRequestIP();
                    $this->doActivityLog(
                        $task,
                        Auth::user(),
                        ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                        LOGNAME_MARK_TASK_COMPLETE,
                        $message
                    );
                }

                return response()->json([
                    'success' => true,
                    'message' => $message,
                ], 200);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(TaskRequest $request, $student_id)
    {
        //
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $task = $this->addTaskAssignee($request, $school_id, $academic_year->id, $student_id);

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
        $task = Task::where('id', $id)->first();

        $array = [];

        $array['task_id'] = $task->id;
        $array['title'] = $task->title;
        $array['to_do_list'] = $task->to_do_list;
        $array['task_date'] = date('d-m-Y H:i:s', strtotime($task->task_date));
        $array['assignee_display'] = ucwords($task->type);
        $array['assignee'] = $task->type;
        $array['reminder_date'] = date('d-m-Y H:i:s', strtotime($task->ReminderValue));

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
    public function edit($id)
    {
        //
        $task = Task::where('id', $id)->first();

        $array = [];

        $array['task_id'] = $task->id;
        $array['title'] = $task->title;
        $array['to_do_list'] = $task->to_do_list;
        $array['task_date'] = date('Y-m-d H:i:s', strtotime($task->task_date));
        $array['assignee'] = $task->type;
        $array['reminder_date'] = date('Y-m-d H:i:s', strtotime($task->ReminderValue));

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
    public function update(TaskRequest $request, $id, $student_id)
    {
        //
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $task = $this->editTaskAssignee($request, $student_id, $id);

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
    public function snooze(Request $request, $id, $student_id)
    {
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $task = Task::where('id', $id)->first();
            if ($task->snooze == 0) {
                $task = $this->snoozeTask($request, $student_id, $id);

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
        //
        try {
            $task = Task::where('id', $id)->first();

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
