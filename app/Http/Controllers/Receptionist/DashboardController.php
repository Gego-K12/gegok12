<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Http\Resources\Receptionist\Task as TaskResource;
use App\Services\DashboardReaderService;
use App\Traits\Dashboard;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use Dashboard;

    public function __construct(protected DashboardReaderService $dashboardReader) {}

    /**
     * Display the receptionist dashboard view.
     *
     * @return View
     */
    public function index()
    {
        $receptionist_id = Auth::id();
        $school_id = Auth::user()->school_id;

        $dashboard = $this->receptionDashboard($school_id, $receptionist_id);

        return view('/reception/dashboard', ['dashboard' => $dashboard]);
    }

    /**
     * Return a list of tasks for the given flag.
     *
     * @param  mixed  $task_flag
     * @return AnonymousResourceCollection
     */
    public function list(Request $request, $task_flag)
    {
        $tasks = $this->dashboardReader->taskWidgetList(Auth::user()->school_id, Auth::id(), $task_flag, $request->q);

        return TaskResource::collection($tasks);
    }

    /**
     * Return counts of pending tasks grouped by flag for the current user.
     *
     * @return array<string,int>
     */
    public function listCount()
    {
        return $this->dashboardReader->taskWidgetCounts(Auth::user()->school_id, Auth::id());
    }
}
