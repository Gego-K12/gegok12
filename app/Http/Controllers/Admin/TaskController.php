<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\Studentlist as StudentlistResource;
use App\Http\Resources\Task as TaskResource;
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
            search: $request->search,
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
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $teachers = SiteHelper::getTeachers(
            Auth::user()->school_id,
            $academic_year->id
        );

        $standardlinks = SiteHelper::getStandardLinkList(
            Auth::user()->school_id
        );

        // Default empty collection
        $students = collect();

        if ($request->standardlink_id) {
            $students = SiteHelper::getClassStudents(
                Auth::user()->school_id,
                $academic_year->id,
                $request->standardlink_id
            );
        }

        return response()->json([
            'standardlinks' => $standardlinks,
            'students' => StudentlistResource::collection($students),
            'teachers' => TeacherResource::collection($teachers),
            'task_date' => now()->format('Y-m-d'),
        ]);
    }

    public function changestatus(Request $request)
    {
        try {
            // if( count($request->selectedTaskCount) > 0 )
            if ($request->selectedTaskCount > 0) {
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

                $res['success'] = $message;

                return $res;
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function index()
    {
        $query = \Request::getQueryString();

        return view('/admin/todolist/index', ['query' => $query]);
    }

    public function create()
    {
        $query = \Request::getQueryString();

        return view('/admin/todolist/create', ['query' => $query]);
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

        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $teachers = SiteHelper::getTeachers($school_id, $academic_year->id);
        $array = [];

        $array['standardlinks'] = SiteHelper::getStandardLinkList($school_id);
        if ($request->standardLink_id != null) {
            $students = SiteHelper::getClassStudents($school_id, $academic_year->id, $request->standardLink_id);
            $array['students'] = StudentlistResource::collection($students);
            $array['standardLink_id'] = $request->standardLink_id;
        } elseif ($task->type == 'student') {
            $students = SiteHelper::getClassStudents($school_id, $academic_year->id, $assignees['standardLinkId']);
            $array['students'] = StudentlistResource::collection($students);
            $array['standardLink_id'] = $assignees['standardLinkId'];
        } else {
            // Previously lost for type='class' - the old code set this
            // inside the assignee loop, then immediately discarded it
            // with `$array = [];` right after, so the edit form never
            // pre-selected the class dropdown.
            $array['standardLink_id'] = $assignees['standardLinkId'];
        }

        $array['teacherlist'] = TeacherResource::collection($teachers);
        $array['task_id'] = $task->id;
        $array['task_assignee_id'] = $assignees['lastTaskAssigneeId'];
        $array['title'] = $task->title;
        $array['to_do_list'] = $task->to_do_list;
        $array['task_date'] = date('d-m-Y H:i:s', strtotime($task->task_date));
        $array['assignee'] = $task->type;
        $array['reminder'] = $task->reminder;
        $array['reminder_date'] = $task->ReminderValue;
        $array['selectedUsers'] = $assignees['selectedUsers'];
        $array['teachers'] = $assignees['selectedTeachers'];

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

        return view('/admin/todolist/edit', ['task' => $task]);
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
