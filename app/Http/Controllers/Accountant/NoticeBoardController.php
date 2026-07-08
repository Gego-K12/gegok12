<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Accountant;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Notice as NoticeResource;
use App\Services\NoticeBoardReaderService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Notice board controller for accountant.
 *
 * Handles listing and rendering of notices for the accountant dashboard.
 */
class NoticeBoardController extends Controller
{
    public function __construct(protected NoticeBoardReaderService $noticeBoardReader) {}

    /**
     * Return active (or optionally expired) notices as a resource collection.
     *
     * @return AnonymousResourceCollection
     */
    public function showList(Request $request)
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $notices = $this->noticeBoardReader->paginatedList(
            schoolId: $school_id,
            academicYearId: $academic_year->id,
            standardLinkIds: null,
            includeNullScope: false,
            excludeTeacherType: false,
            includeExpired: $request->showExpired == 'true',
            standardLinkFilter: $request->standardLink_id ?: null,
            search: $request->search ?: null,
        );

        return NoticeResource::collection($notices);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $query = \Request::getQueryString();

        return view('/accountant/noticeboard/index', ['query' => $query]);
    }

    public function list()
    {
        return $this->noticeBoardReader->filterOptions(Auth::user()->school_id);
    }
}
