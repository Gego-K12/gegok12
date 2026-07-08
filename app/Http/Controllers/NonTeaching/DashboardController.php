<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\NonTeaching;

use App\Http\Controllers\Controller;
use App\Traits\Dashboard;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use Dashboard;

    /**
     * Display the non-teaching staff dashboard view.
     *
     * @return View
     */
    public function index()
    {
        $dashboard = $this->nonTeachingDashboard(Auth::user()->school_id);

        return view('/nonteaching/dashboard', ['dashboard' => $dashboard]);
    }
}
