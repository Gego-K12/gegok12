<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Helpers\SiteHelper;

/**
 * Class NavigationController
 *
 * Handles academic year navigation and selection for admin users.
 * Responsible for fetching academic year lists and updating
 * the currently selected academic year in cache.
 *
 * @package App\Http\Controllers\Admin
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

        $academic_year = config('academy_backend.enabled')
            ? collect([SiteHelper::getAcademicYear($school_id)])
            : AcademicYear::where('school_id', $school_id)->orderBy('name', 'ASC')->get();

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
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $school = (int) Auth::user()->school_id;
        $request->validate(['academic_year_id' => ['required', 'integer',
            \Illuminate\Validation\Rule::exists('academic_years', 'id')->where('school_id', $school)->whereNull('deleted_at')]]);
        if (!config('academy_backend.enabled')) {
            $request->session()->put('academic_year.'.$school, (int) $request->academic_year_id);
        }
        return response()->json(['current_year' => SiteHelper::getAcademicYear($school)]);

    }
}
