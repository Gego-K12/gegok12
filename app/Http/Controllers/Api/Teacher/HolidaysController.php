<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api\Teacher;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Teacher\Holiday as HolidayResource;
use App\Services\HolidaysReaderService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class HolidaysController extends Controller
{
    public function __construct(protected HolidaysReaderService $holidaysReader) {}

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $holidays = $this->holidaysReader->paginatedList($school_id, $academic_year->id);

        return response()->json([
            'success' => true,
            'message' => 'Holiday List',
            'data' => HolidayResource::collection($holidays),
            'count' => $holidays->total(),
        ], 200);
    }
}
