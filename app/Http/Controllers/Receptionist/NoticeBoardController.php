<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Receptionist;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Notice as NoticeResource;
use App\Services\NoticeBoardReaderService;
use App\Traits\Common;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class NoticeBoardController extends Controller
{
    use Common;

    public function __construct(protected NoticeBoardReaderService $noticeBoardReader) {}

    public function list(Request $request)
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

        return view('/reception/noticeboard/index', ['query' => $query]);
    }

    public function noticelist()
    {
        return $this->noticeBoardReader->filterOptions(Auth::user()->school_id);
    }
}
