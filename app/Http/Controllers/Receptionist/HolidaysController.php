<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Receptionist;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Holiday as HolidayResource;
use App\Services\HolidaysReaderService;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class HolidaysController extends Controller
{
    use Common;
    use LogActivity;

    public function __construct(protected HolidaysReaderService $holidaysReader) {}

    /**
     * Display a listing of the resource.
     *
     * @return Response
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
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('/reception/holiday/index');
    }
}
