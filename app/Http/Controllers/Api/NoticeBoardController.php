<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Notice as NoticeSchoolResource;
use App\Models\User;
use App\Services\NoticeBoardReaderService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class NoticeBoardController extends Controller
{
    public function __construct(protected NoticeBoardReaderService $noticeBoardReader) {}

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function indexSchool()
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $notice = $this->noticeBoardReader->list(
            schoolId: $school_id,
            academicYearId: $academic_year->id,
            standardLinkIds: [],
            includeNullScope: true,
            excludeTeacherType: true,
        );

        return response()->json([
            'success' => true,
            'message' => 'Notice List',
            'type' => 'school',
            'data' => NoticeSchoolResource::collection($notice),
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function expiredSchool()
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $notice = $this->noticeBoardReader->list(
            schoolId: $school_id,
            academicYearId: $academic_year->id,
            standardLinkIds: [],
            includeNullScope: true,
            excludeTeacherType: true,
            expired: true,
        );

        return response()->json([
            'success' => true,
            'message' => 'Expired Notice List',
            'type' => 'school',
            'data' => NoticeSchoolResource::collection($notice),
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function indexClass($student_id)
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $student = User::where('id', $student_id)->first();

        $notice = $this->noticeBoardReader->list(
            schoolId: $school_id,
            academicYearId: $academic_year->id,
            standardLinkIds: [$student->studentAcademicLatest->standardLink_id],
            includeNullScope: false,
            excludeTeacherType: true,
        );

        return response()->json([
            'success' => true,
            'message' => 'Notice List',
            'type' => 'class',
            'data' => NoticeSchoolResource::collection($notice),
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function expiredClass($student_id)
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $student = User::where('id', $student_id)->first();

        $notice = $this->noticeBoardReader->list(
            schoolId: $school_id,
            academicYearId: $academic_year->id,
            standardLinkIds: [$student->studentAcademicLatest->standardLink_id],
            includeNullScope: false,
            excludeTeacherType: true,
            expired: true,
        );

        return response()->json([
            'success' => true,
            'message' => 'Expired Notice List',
            'type' => 'class',
            'data' => NoticeSchoolResource::collection($notice),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $notice = $this->noticeBoardReader->find($id, Auth::user()->school_id, excludeType: 'teacher');

        return response()->json([
            'success' => true,
            'message' => 'Show Notice',
            'data' => $notice ? NoticeSchoolResource::collection([$notice]) : [],
        ], 200);
    }
}
