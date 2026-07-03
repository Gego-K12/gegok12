<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Http\Resources\Accountant\Task as TaskResource;
use App\Models\Task;
use App\Traits\Dashboard;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
    public function index()
    {
        $librarian_id = Auth::id();
        $school_id = Auth::user()->school_id;
        $dashboard = $this->librarianDashboard($school_id, $librarian_id);

        return view('/library/dashboard', ['dashboard' => $dashboard]);
    }

    /**
     * Return a collection of tasks for the authenticated library filtered by flag.
     *
     * @param  int|string  $task_flag
     * @return AnonymousResourceCollection
     */
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

    /**
     * Return task counts grouped by flag for the authenticated library.
     *
     * @return array|\\Illuminate\\Support\\Collection
     */
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
