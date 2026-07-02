<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SectionRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Traits\AcademicProcess;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Models\Section;
use App\Traits\Common;
use Exception;

/**
 * Class SectionController
 *
 * Handles creation and management of academic sections
 * for the authenticated school under admin module.
 *
 * @package App\Http\Controllers\Admin
 */
class SectionController extends Controller
{
    use AcademicProcess;
    use LogActivity;
    use Common;

    /**
     * Store a newly created section in storage.
     *
     * Creates a new section for the authenticated user's school
     * using academic process trait and logs the activity.
     *
     * @param SectionRequest $request
     * @return array
     */
    public function store(SectionRequest $request)
    {
        //
        try
        {
            $school_id = Auth::user()->school_id;
          
            $section = $this->createSection($school_id , $request);

            $message = trans('messages.add_success_msg',['module' => 'Section']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $section,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_SECTION,
                $message
            );
            
            $res['success'] = trans('messages.add_success_msg',['module' => 'Section']);

            return $res;
        }
        catch(Exception $e)
        {
        }
    }
}
