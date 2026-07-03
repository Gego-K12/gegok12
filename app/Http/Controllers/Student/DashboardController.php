<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Student\Task as TaskResource;
use App\Models\Task;
use App\Models\User;
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
        $school_id = Auth::user()->school_id;
        $student_id = Auth::id();
        $student = User::where('id', $student_id)->first();
        $standardLink_id = $student->studentAcademicLatest->standardLink_id;

        if ($request->exam_date != null) {
            $exam_date = date('Y-m-d H:i:s', strtotime($request->exam_date));
        }

        $dashboard = $this->studentDashboard($school_id, $student, $standardLink_id, $request->subject, $request->exam, $request->mark, $exam_date);

        return view('/student/dashboard/dashboard', ['dashboard' => $dashboard]);
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
