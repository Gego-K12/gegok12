<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Receptionist;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\FeedReaderService;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    use Common;

    //
    use LogActivity;

    public function __construct(protected FeedReaderService $feedReader) {}

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $academicYearId = SiteHelper::getAcademicYear($schoolId)->id;

        $query = Post::query();
        $this->feedReader->scopeToTenant($query, $schoolId, $academicYearId);

        if (! $this->feedReader->applyListOrSearchFilter($query, $request->list, $request->search, $schoolId)) {
            $query->where('visibility', 'all_class');
        }

        $feeds = $query->orderBy('posted_at', 'desc')->get();

        return view('/reception/feed/feed', ['feeds' => $feeds]);
    }
}
