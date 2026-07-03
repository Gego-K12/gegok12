<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Student;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Student\Notice as NoticeResource;
use App\Models\NoticeBoard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class NoticeBoardController extends Controller
{
    public function list(Request $request)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $notices = NoticeBoard::where([['school_id', $school_id], ['academic_year_id', $academic_year->id], ['type', '!=', 'teacher']])->where([['expire_date', '>=', date('Y-m-d')], ['status', 1]])->where('standardLink_id', null)->orWhere('standardLink_id', Auth::user()->studentAcademicLatest->standardLink_id);

        if (count((array) \Request::getQueryString()) > 0) {
            if ($request->showExpired == 'true') {
                $notices = $notices->orWhere([['status', 0], ['expire_date', '<=', date('Y-m-d')]]);
            }
            if ($request->search != '') {
                $notice = $notices->where('title', 'LIKE', '%'.$request->search.'%')->orWhere('description', 'LIKE', '%'.$request->search.'%');
            }
        }

        $notices = $notices->paginate(10);

        $notices = NoticeResource::collection($notices);

        return $notices;
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
