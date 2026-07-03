<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Teacher;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller; // new
use App\Http\Resources\backgroundImagesResource;  // new
use App\Http\Resources\StandardLink as StandardLinkResource;
use App\Http\Resources\Teacher\Notice as NoticeResource;
use App\Models\BackgroundImage; // new
use App\Models\NoticeBoard;
use App\Models\StandardLink;
use App\Models\Teacherlink;
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

        $standardLinks = StandardLink::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
            ['class_teacher_id', Auth::id()],
        ])->pluck('id')->toArray();

        $teacherlinks = Teacherlink::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
            ['teacher_id', Auth::id()],
        ])->pluck('standardLink_id')->toArray();

        $standards = array_merge($standardLinks, $teacherlinks);
        array_push($standards, null);

        $notices = NoticeBoard::where([['school_id', $school_id], ['academic_year_id', $academic_year->id]])->where([['expire_date', '>=', date('Y-m-d')], ['status', 1]])->orWhereIn('standardLink_id', $standards); // check with standards

        if (count((array) \Request::getQueryString()) > 0) {
            if ($request->showExpired == 'true') {
                $notices = $notices->orWhere([['status', 0], ['expire_date', '<=', date('Y-m-d')]]);
            }
            if ($request->standardLink_id != '') {
                $notices = $notices->where('standardLink_id', $request->standardLink_id);
            }

            if ($request->search != '') {
                $notices = $notices->where('title', 'LIKE', '%'.$request->search.'%')->orWhere('description', 'LIKE', '%'.$request->search.'%');
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

        return view('/teacher/noticeboard/index', ['query' => $query]);
    }

    // new
    public function noticelist()
    {
        //
        $standardLink = StandardLink::with('standard', 'section')->where('school_id', Auth::user()->school_id)->get();
        $backgroundimages = BackgroundImage::where('school_id', Auth::user()->school_id)->latest()->get();
        $backgroundimages = backgroundImagesResource::collection($backgroundimages);
        $standardLink = StandardLinkResource::collection($standardLink);

        $array = [];

        $array['standardLinklist'] = $standardLink;
        $array['backgroundimages'] = $backgroundimages;

        return $array;
    }
}
