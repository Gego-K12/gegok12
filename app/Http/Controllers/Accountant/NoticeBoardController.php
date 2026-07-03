<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Accountant;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller; // new
use App\Http\Resources\backgroundImagesResource;  // new
use App\Http\Resources\Notice as NoticeResource;
use App\Http\Resources\StandardLink as StandardLinkResource;
use App\Models\BackgroundImage; // new
use App\Models\NoticeBoard;
use App\Models\StandardLink;
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
    /**
     * Return active (or optionally expired) notices as a resource collection.
     *
     * @return AnonymousResourceCollection
     */
    public function showList(Request $request)
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $notices = NoticeBoard::where([['school_id', Auth::user()->school_id], ['academic_year_id', $academic_year->id]])->where('expire_date', '>=', date('Y-m-d'))->where('status', 1);
        if (count((array) \Request::getQueryString()) > 0) {
            if ($request->showExpired == 'true') {
                $notices = $notices->orWhere('status', 0)->orWhere('expire_date', '<=', date('Y-m-d'));
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

        return view('/accountant/noticeboard/index', ['query' => $query]);
    }

    // new
    public function list()
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
