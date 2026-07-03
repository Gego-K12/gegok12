<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Student;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Student\Notice as NoticeResource;
use App\Services\NoticeBoardReaderService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class NoticeBoardController extends Controller
{
    public function __construct(protected NoticeBoardReaderService $noticeBoardReader) {}

    public function list(Request $request)
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $classId = Auth::user()->studentAcademicLatest->standardLink_id;

        $notices = $this->noticeBoardReader->paginatedList(
            schoolId: $school_id,
            academicYearId: $academic_year->id,
            standardLinkIds: [$classId],
            includeNullScope: true,
            excludeTeacherType: true,
            includeExpired: $request->showExpired == 'true',
            standardLinkFilter: null,
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

        return view('/student/noticeboard/index', ['query' => $query]);
    }
}
