<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\Teacher\Task as TaskResource;
use App\Services\DashboardReaderService;
use App\Traits\Dashboard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use Dashboard;

    public function __construct(protected DashboardReaderService $dashboardReader) {}

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
        $tasks = $this->dashboardReader->taskWidgetList(Auth::user()->school_id, Auth::id(), $task_flag, $request->q);

        return TaskResource::collection($tasks);
    }

    public function listCount()
    {
        return $this->dashboardReader->taskWidgetCounts(Auth::user()->school_id, Auth::id());
    }
}
