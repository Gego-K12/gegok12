<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\Teacher\Task as TaskResource;
use App\Models\Task;
use App\Traits\Dashboard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use Dashboard;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        //
        $teacher_id = Auth::id();
        $school_id = Auth::user()->school_id;

        $dashboard = $this->teacherDashboard($school_id, $teacher_id);

        return view('/teacher/dashboard/dashboard', ['dashboard' => $dashboard]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function timetable(Request $request)
    {
        //
        $teacher_id = Auth::id();
        $school_id = Auth::user()->school_id;

        $dashboard = $this->teacherDashboard($school_id, $teacher_id);

        return $dashboard['timetable'];
    }

    public function list(Request $request, $task_flag)
    {
        //
        $tasks = Task::where([['school_id', Auth::user()->school_id], ['task_status', 0], ['task_flag', $task_flag]])->ByType('to_me', Auth::id());

        if ($request->q != null) {
            $tasks = $tasks->where('title', 'LIKE', '%'.$request->q.'%');
        }
        $tasks = $tasks->get();

        $tasks = TaskResource::collection($tasks);

        return $tasks;
    }

    public function listCount()
    {
        //
        $tasks = Task::where([['school_id', Auth::user()->school_id], ['user_id', Auth::id()], ['task_status', 0]])->ByType('to_me', Auth::id())->get()->groupBy('Flag');

        foreach ($tasks as $key => $value) {
            $tasks[$key] = count($value);
        }

        return $tasks;
    }
}
