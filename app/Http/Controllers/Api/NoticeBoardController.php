<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Notice as NoticeSchoolResource;
use App\Models\NoticeBoard;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class NoticeBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function indexSchool()
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $notice = NoticeBoard::where([['school_id', Auth::user()->school_id], ['academic_year_id', $academic_year->id], ['standardLink_id', null], ['type', '!=', 'teacher']])->where('expire_date', '>=', date('Y-m-d'))->where('status', 1)->get();
        $noticelist = NoticeSchoolResource::collection($notice);

        return response()->json([
            'success' => true,
            'message' => 'Notice List',
            'type' => 'school',
            'data' => $noticelist,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function expiredSchool()
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $notice = NoticeBoard::where([['school_id', Auth::user()->school_id], ['academic_year_id', $academic_year->id], ['standardLink_id', null], ['type', '!=', 'teacher']])->where('expire_date', '<=', date('Y-m-d'))->where('status', 0)->get();
        $noticelist = NoticeSchoolResource::collection($notice);

        return response()->json([
            'success' => true,
            'message' => 'Expired Notice List',
            'type' => 'school',
            'data' => $noticelist,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function indexClass($student_id)
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $student = User::where('id', $student_id)->first();
        $notice = NoticeBoard::where([['school_id', Auth::user()->school_id], ['academic_year_id', $academic_year->id], ['standardLink_id', $student->studentAcademicLatest->standardLink_id], ['type', '!=', 'teacher']])->where('expire_date', '>=', date('Y-m-d'))->where('status', 1)->get();
        $noticelist = NoticeSchoolResource::collection($notice);

        return response()->json([
            'success' => true,
            'message' => 'Notice List',
            'type' => 'class',
            'data' => $noticelist,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function expiredClass($student_id)
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $student = User::where('id', $student_id)->first();
        $notice = NoticeBoard::where([['school_id', Auth::user()->school_id], ['academic_year_id', $academic_year->id], ['standardLink_id', $student->studentAcademicLatest->standardLink_id], ['type', '!=', 'teacher']])->where('expire_date', '<=', date('Y-m-d'))->where('status', 0)->get();
        $noticelist = NoticeSchoolResource::collection($notice);

        return response()->json([
            'success' => true,
            'message' => 'Expired Notice List',
            'type' => 'class',
            'data' => $noticelist,
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
        //
        $notice = NoticeBoard::where('id', $id)->where('type', '!=', 'teacher')->get();
        $noticelist = NoticeSchoolResource::collection($notice);

        return response()->json([
            'success' => true,
            'message' => 'Show Notice',
            'data' => $noticelist,
        ], 200);
    }
}
