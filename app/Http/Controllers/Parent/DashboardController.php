<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Parents have no web portal of their own -- they use the mobile app.
     * This page exists only so logging in with a parent account doesn't
     * 404 (MustBeSchoolAdmin redirects here); it just points them at the app.
     *
     * @return View
     */
    public function index()
    {
        return view('parent.dashboard');
    }
}
