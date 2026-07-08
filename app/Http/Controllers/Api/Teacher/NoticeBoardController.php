<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api\Teacher;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Teacher\Notice as NoticeSchoolResource;
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
            excludeTeacherType: false,
        );

        return response()->json([
            'success' => true,
            'message' => 'Notice List',
            'type' => 'school',
            'data' => NoticeSchoolResource::collection($notice),
        ], 200);
    }

    /**
     * Notices for the classes a teacher is linked to (class teacher or
     * subject teacher). Previously only checked subject-teacher
     * assignments and had no school/academic-year scoping - now shares
     * the same resolution the web dashboard uses.
     */
    public function showNotices($teacher_id)
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $teacher = User::where('id', $teacher_id)->first();

        $standardLinks = $this->noticeBoardReader->standardLinksForTeacher(
            $school_id,
            $academic_year->id,
            $teacher->id
        );

        $notice = $this->noticeBoardReader->list(
            schoolId: $school_id,
            academicYearId: $academic_year->id,
            standardLinkIds: $standardLinks,
            includeNullScope: false,
            excludeTeacherType: false,
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
            excludeTeacherType: false,
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $notice = $this->noticeBoardReader->find($id, Auth::user()->school_id, requireType: 'teacher');

        return response()->json([
            'success' => true,
            'message' => 'Show Notice',
            'data' => $notice ? NoticeSchoolResource::collection([$notice]) : [],
        ], 200);
    }
}
