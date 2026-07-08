<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

/**
 * Class NavigationController
 *
 * Handles academic year navigation and selection for admin users.
 * Responsible for fetching academic year lists and updating
 * the currently selected academic year in cache.
 */
class NavigationController extends Controller
{
    /**
     * Get the list of academic years for the logged-in user's school.
     *
     * Returns all academic years ordered by name along with
     * the currently active academic year.
     *
     * @return string JSON encoded academic year list and current year
     */
    public function list()
    {
        $school_id = Auth::user()->school_id;

        $academic_year = AcademicYear::where('school_id', $school_id)
            ->orderBy('name', 'ASC')
            ->get();

        $current_year = SiteHelper::getAcademicYear($school_id);

        $array = [];

        $array['academiclist'] = $academic_year;
        $array['current_year'] = $current_year;

        return json_encode($array);
    }

    /**
     * Set the selected academic year in cache.
     *
     * Clears existing academic year cache values and
     * stores the newly selected academic year ID.
     *
     * @return void
     */
    public function index(Request $request)
    {
        $academic_year_id = $request->academic_year_id;

        Cache::forget('academic_year');
        Cache::forget('academic_year_for_school_'.Auth::user()->school_id);

        Cache::remember('academic_year', env('CACHE_TIME'), function () use ($academic_year_id) {
            return $academic_year_id;
        });

        $current_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
    }
}
