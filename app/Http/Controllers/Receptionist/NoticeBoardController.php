<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Receptionist;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller; // new
use App\Http\Resources\backgroundImagesResource;  // new
use App\Http\Resources\Notice as NoticeResource;
use App\Http\Resources\StandardLink as StandardLinkResource;
use App\Models\BackgroundImage; // new
use App\Models\NoticeBoard;
use App\Models\StandardLink;
use App\Traits\Common;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class NoticeBoardController extends Controller
{
    use Common;

    public function list(Request $request)
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $notice = NoticeBoard::where([['school_id', Auth::user()->school_id], ['academic_year_id', $academic_year->id]])->where('expire_date', '>=', date('Y-m-d'))->where('status', 1);
        if (count((array) \Request::getQueryString()) > 0) {
            if ($request->showExpired == 'true') {
                $notice = $notice->orWhere('status', 0)->orWhere('expire_date', '<=', date('Y-m-d'));
            }

            if ($request->standardLink_id != '') {
                $notice = $notice->where('standardLink_id', $request->standardLink_id);
            }
            if ($request->search != '') {
                $notice = $notice->where('title', 'LIKE', '%'.$request->search.'%')->orWhere('description', 'LIKE', '%'.$request->search.'%');
            }
        }
        $notice = $notice->paginate(10);
        $noticelist = NoticeResource::collection($notice);

        return $noticelist;
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
