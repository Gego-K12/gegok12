<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Librarian;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\Librarian\Task as TaskResource;
use App\Http\Resources\Teacher as TeacherResource;
use App\Http\Resources\User as UserResource;
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
     * Display a listing of the resource.
     *
     * @return Response
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

    public function index()
    {
        $query = \Request::getQueryString();

        return view('/library/todolist/index', ['query' => $query]);
    }

    public function create()
    {
        $query = \Request::getQueryString();

        return view('/library/todolist/create', ['query' => $query]);
    }

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

        $assignees = $this->taskReader->resolveAssignees($task);
        $selected_students = null;
        $selected_teachers = null;

        if ($task->type == 'student') {
            $selected_students = UserResource::collection(User::whereIn('id', $assignees['selectedUsers'])->get());
        }
        if ($task->type == 'teacher') {
            $selected_teachers = TeacherResource::collection(User::whereIn('id', $assignees['selectedTeachers'])->get());
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $task = $this->taskReader->find($id, Auth::user()->school_id);

        if (! $task) {
            abort(404);
        }

        return view('/library/todolist/edit', ['task' => $task]);
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

            $res['success'] = $message;

            return $res;
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

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
