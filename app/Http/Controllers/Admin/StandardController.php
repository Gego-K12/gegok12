<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StandardRequest;
use App\Models\Standard;
use App\Traits\AcademicProcess;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Class StandardController
 *
 * Handles standard (class) creation and setup
 * operations for the admin module.
 */
class StandardController extends Controller
{
    use AcademicProcess;
    use Common;
    use LogActivity;

    /**
     * Store a newly created standard.
     *
     * Creates a standard for the authenticated
     * school and logs the activity.
     *
     * @return array
     */
    public function store(StandardRequest $request)
    {
        //
        try {
            $school_id = Auth::user()->school_id;

            $standard = $this->createStandard($school_id, $request);

            $message = trans('messages.add_success_msg', ['module' => 'Standard']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $standard,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_STANDARD,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
        }
    }

    /**
     * Show the form for creating a new standard.
     *
     * Loads current academic year details
     * for standard creation.
     *
     * @return Response
     */
    public function create()
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        return view(
            '/admin/school/standards/add',
            ['academic_year_id' => $academic_year->id]
        );
    }

    /**
     * Add standard during initial school setup.
     *
     * Used in standard setup flow and logs
     * the setup activity.
     *
     * @return array
     */
    public function add(Request $request)
    {
        //
        try {
            $school_id = Auth::user()->school_id;

            $standard = $this->addStandard($school_id, $request);

            $message = trans('messages.standard_setup_success_msg');

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $standard,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_STANDARD_SETUP,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
        }
    }
}
