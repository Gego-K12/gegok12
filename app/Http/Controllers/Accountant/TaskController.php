<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Accountant;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\Accountant\Task as TaskResource;
use App\Http\Resources\Teacher as TeacherResource;
use App\Http\Resources\User as UserResource;
use App\Models\Task;
use App\Models\TaskAssignee;
use App\Models\User;
use App\Models\Users\TeacherUser;
use App\Services\TaskReaderService;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\TodolistProcess;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Log;

/**
 * Class TaskController
 *
 * Handles task (to-do list) management for the accountant dashboard.
 *
 * Responsibilities:
 * - List tasks by type and status
 * - Create and assign tasks
 * - Update and snooze tasks
 * - Mark tasks as completed
 * - Delete tasks
 * - Log all task-related activities
 */
class TaskController extends Controller
{
    use Common;
    use LogActivity;
    use TodolistProcess;

    public function __construct(protected TaskReaderService $taskReader) {}

    /**
     * Retrieve tasks filtered by type and status.
     *
     * Tasks are grouped by task flag.
     *
     * @return Collection
     */
    public function showlist(Request $request)
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $tasks = $this->taskReader->listByType(
            schoolId: $school_id,
            academicYearId: $academic_year->id,
            type: $request->type,
            userId: Auth::id(),
            status: $request->status,
        );

        $tasks = TaskResource::collection($tasks)->groupby('task_flag');

        return $tasks;
    }

    /**
     * Return an empty task list response.
     *
     * @return JsonResponse
     */
    public function list(Request $request)
    {
        $tasks = [];

        return response()->json($tasks);
    }

    /**
     * Mark selected tasks as completed.
     *
     * @return array<string, string>|null
     */
    // public function changestatus(Request $request)
    // {
    //     try {
    //         if ($request->selectedTaskCount > 0) {
    //             foreach ($request->task_completed as $task_id) {
    //                 $task = Task::where('id', $task_id)->first();

    //                 $task->task_status = 1;
    //                 $task->save();

    //                 $message = trans('messages.task_check_success_msg');

    //                 $ip = $this->getRequestIP();
    //                 $this->doActivityLog(
    //                     $task,
    //                     Auth::user(),
    //                     ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
    //                     LOGNAME_MARK_TASK_COMPLETE,
    //                     $message
    //                 );
    //             }

    //             $res['success'] = $message;
    //             return $res;
    //         }
    //     } catch (Exception $e) {
    //         Log::info($e->getMessage());
    //     }
    // }
    public function changestatus(Request $request)
    {
        DB::beginTransaction();

        try {

            foreach ($request->task_completed as $id) {
                $assignee = TaskAssignee::where([
                    ['task_id', $id],
                    ['user_id', Auth::id()],
                ])->first();

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
                'success' => $message,
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
     * Display the task listing page.
     *
     * @return View
     */
    public function index()
    {
        $query = \Request::getQueryString();

        return view('/accountant/todolist/index', ['query' => $query]);
    }

    /**
     * Display the task creation page.
     *
     * @return View
     */
    public function create()
    {
        $query = \Request::getQueryString();

        return view('/accountant/todolist/create', ['query' => $query]);
    }

    /**
     * Store a newly created task and assign users.
     *
     * @return array<string, string>|null
     */
    public function store(TaskRequest $request)
    {
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

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Retrieve task details for viewing.
     *
     * @param  int  $id
     * @return array<string, mixed>
     */
    public function show($id)
    {
        $task = $this->taskReader->find($id, Auth::user()->school_id);

        if (! $task) {
            abort(404);
        }

        $assignees = $this->taskReader->resolveAssignees($task);
        $selected_students = null;
        $selected_teachers = null;

        if ($task->type == 'student') {
            $selected_students = UserResource::collection(User::whereIn('id', $assignees['selectedUsers'])->get());
        }
        if ($task->type == 'teacher') {
            $selected_teachers = TeacherResource::collection(TeacherUser::whereIn('id', $assignees['selectedTeachers'])->get());
        }

        return [
            'task_id' => $task->id,
            'task_assignee_id' => $assignees['lastTaskAssigneeId'],
            'title' => $task->title,
            'to_do_list' => $task->to_do_list,
            'task_date' => date('d-m-Y H:i:s', strtotime($task->task_date)),
            'assignee_display' => ucwords($task->type),
            'assignee' => $task->type,
            'reminder_date' => date('d-m-Y H:i:s', strtotime($task->ReminderValue)),
            'selectedUsers' => $selected_students,
            'standardLink_id' => $assignees['standardLinkId'],
            'class' => $assignees['className'],
            'teachers' => $selected_teachers,
        ];
    }

    /**
     * Retrieve task data for editing.
     *
     * @param  int  $id
     * @return array<string, mixed>
     */
    public function editList(Request $request, $id)
    {
        $task = $this->taskReader->find($id, Auth::user()->school_id);

        if (! $task) {
            abort(404);
        }

        $assignees = $this->taskReader->resolveAssignees($task);

        $array = [];

        $array['task_date'] = date('Y-m-d');
        $array['task_id'] = $task->id;
        $array['task_assignee_id'] = $assignees['lastTaskAssigneeId'];
        $array['title'] = $task->title;
        $array['to_do_list'] = $task->to_do_list;
        $array['task_date'] = date('d-m-Y H:i:s', strtotime($task->task_date));
        $array['assignee'] = $task->type;
        $array['reminder'] = $task->reminder;

        return $array;
    }

    /**
     * Show the task edit page.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $task = $this->taskReader->find($id, Auth::user()->school_id);

        if (! $task) {
            abort(404);
        }

        return view('/accountant/todolist/edit', ['task' => $task]);
    }

    /**
     * Update the specified task.
     *
     * @param  int  $id
     * @return array<string, string>|null
     */
    public function update(TaskRequest $request, $id)
    {
        $school_id = Auth::user()->school_id;

        if (! $this->taskReader->find($id, $school_id)) {
            abort(404);
        }

        try {
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

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Snooze the specified task.
     *
     * @param  int  $id
     * @return array<string, string>|null
     */
    public function snooze(Request $request, $id)
    {
        $school_id = Auth::user()->school_id;
        $task = $this->taskReader->find($id, $school_id);

        if (! $task) {
            abort(404);
        }

        try {
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
     * Delete the specified task.
     *
     * @param  int  $id
     * @return array<string, string>|null
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

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
