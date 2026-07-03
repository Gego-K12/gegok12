<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\HolidayAddRequest;
use App\Http\Requests\HolidayUpdateRequest;
use App\Http\Resources\Holiday as HolidayResource;
use App\Models\Events;
use App\Services\HolidaysReaderService;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Class HolidaysController
 *
 * Handles CRUD operations for school holidays in the admin panel.
 * This controller is responsible for:
 * - Listing holidays (API & view)
 * - Creating holidays
 * - Updating holidays
 * - Deleting holidays
 * - Logging holiday-related activities
 */
class HolidaysController extends Controller
{
    use Common;
    use LogActivity;

    public function __construct(protected HolidaysReaderService $holidaysReader) {}

    /**
     * Get a paginated list of holidays for the current school and academic year.
     *
     * Returned as an API resource collection.
     *
     * @return AnonymousResourceCollection
     */
    public function list()
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        return HolidayResource::collection(
            $this->holidaysReader->paginatedList($school_id, $academic_year->id)
        );
    }

    /**
     * Display the holidays index page.
     *
     * @return View
     */
    public function index()
    {
        //
        return view('/admin/school/holidays/index');
    }

    /**
     * Provide default values required for creating a holiday.
     *
     * Used for frontend initialization.
     *
     * @return array<string, string>
     */
    public function createList()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $array = [];
        $array['start_date'] = date('Y-m-d');

        return $array;
    }

    /**
     * Show the holiday creation form.
     *
     * @return View
     */
    public function create()
    {
        //
        return view('/admin/school/holidays/create');
    }

    /**
     * Store one or more holidays in storage.
     *
     * Supports bulk holiday creation.
     *
     * @return array<string, string>|null
     */
    public function store(HolidayAddRequest $request)
    {
        //
        try {
            for ($i = 0; $i < $request->count; $i++) {
                $date = 'date'.$i;
                $title = 'title'.$i;

                $school_id = Auth::user()->school_id;
                $academic_year = SiteHelper::getAcademicYear($school_id);

                $holiday = new Events;

                $holiday->school_id = $school_id;
                $holiday->academic_year_id = $academic_year->id;
                $holiday->select_type = 'school';
                $holiday->title = $request->$title;
                $holiday->category = 'holidays';
                $holiday->start_date = date('Y-m-d', strtotime($request->$date));
                $holiday->end_date = date('Y-m-d', strtotime($request->$date));

                $holiday->save();

                $message = trans('messages.add_success_msg', ['module' => 'Holidays']);

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    $holiday,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_ADD_HOLIDAY,
                    $message
                );
            }

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
        }
    }

    /**
     * Retrieve holiday data for editing.
     *
     * @param  int  $id
     * @return array<string, string>
     */
    public function edit($id)
    {
        $holiday = $this->holidaysReader->find($id, Auth::user()->school_id);

        if (! $holiday) {
            abort(404);
        }

        $array = [];
        $array['date'] = date('Y-m-d', strtotime($holiday->start_date));
        $array['title'] = $holiday->title;

        return $array;
    }

    /**
     * Update the specified holiday.
     *
     * @param  int  $id
     * @return array<string, string>|null
     */
    public function update(HolidayUpdateRequest $request, $id)
    {
        $holiday = $this->holidaysReader->find($id, Auth::user()->school_id);

        if (! $holiday) {
            abort(404);
        }

        try {
            $holiday->title = $request->title;
            $holiday->start_date = date('Y-m-d', strtotime($request->date));
            $holiday->end_date = date('Y-m-d', strtotime($request->date));

            $holiday->save();

            $message = trans('messages.update_success_msg', ['module' => 'Holiday']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $holiday,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_HOLIDAY,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
        }
    }

    /**
     * Delete the specified holiday.
     *
     * @param  int  $id
     * @return array<string, string>|null
     */
    public function destroy($id)
    {
        $holiday = $this->holidaysReader->find($id, Auth::user()->school_id);

        if (! $holiday) {
            abort(404);
        }

        try {
            $holiday->delete();

            $message = trans('messages.delete_success_msg', ['module' => 'Holiday']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $holiday,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_HOLIDAY,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
        }
    }
}
