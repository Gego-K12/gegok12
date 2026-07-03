<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Librarian;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Holiday as HolidayResource;
use App\Models\Events;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class HolidaysController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function list()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $holidays = Events::where([['school_id', $school_id], ['academic_year_id', $academic_year->id], ['category', 'holidays']])->orderBy('start_date', 'ASC')->paginate(10);
        $holidays = HolidayResource::collection($holidays);

        return $holidays;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        return view('/library/holiday/index');
    }
}
