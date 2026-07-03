<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api\Teacher;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Teacher\Notice as NoticeSchoolResource;
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
        $notice = NoticeBoard::where([['school_id', Auth::user()->school_id], ['standardLink_id', null]])->where('expire_date', '>=', date('Y-m-d'))->where('status', 1)->get();
        $noticelist = NoticeSchoolResource::collection($notice);

        return response()->json([
            'success' => true,
            'message' => 'Notice List',
            'type' => 'school',
            'data' => $noticelist,
        ], 200);
    }

    public function showNotices($teacher_id)
    {
        //
        $standardLinks = [];
        $teacher = User::where('id', $teacher_id)->first();

        if (count($teacher->teacherlink) > 0) {
            $standardLinks = $teacher->teacherlink->pluck('standardLink_id')->toArray();
        }

        $notice = NoticeBoard::whereIn('standardLink_id', $standardLinks)->where('expire_date', '>=', date('Y-m-d'))->where('status', 1)->get();

        // $notice = NoticeBoard::where([['school_id',Auth::user()->school_id],['standardLink_id',null]])->where('expire_date','>=',date('Y-m-d'))->where('status',1)->get();
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
        $notice = NoticeBoard::where([['school_id', Auth::user()->school_id], ['standardLink_id', null]])->where('expire_date', '<=', date('Y-m-d'))->where('status', 0)->get();
        $noticelist = NoticeSchoolResource::collection($notice);

        return response()->json([
            'success' => true,
            'message' => 'Expired Notice List',
            'type' => 'school',
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
        $notice = NoticeBoard::where('id', $id)->where('type', '==', 'teacher')->get();
        $noticelist = NoticeSchoolResource::collection($notice);

        return response()->json([
            'success' => true,
            'message' => 'Show Notice',
            'data' => $noticelist,
        ], 200);
    }
}
