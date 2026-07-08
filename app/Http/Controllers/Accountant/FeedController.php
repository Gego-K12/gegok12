<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Accountant;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\FeedReaderService;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Class FeedController
 *
 * Handles classwall feed listing and filtering
 * for the accountant dashboard.
 *
 * Responsibilities:
 * - Display classwall feeds
 * - Filter feeds by visibility
 * - Filter feeds by tags
 * - Prepare banner images for feed views
 */
class FeedController extends Controller
{
    use Common;

    /**
     * Controller for account feed (classwall) listing and filtering.
     */
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

        $feeds = $this->scopedFeedQuery($request, $schoolId, $academicYearId)
            ->orderBy('posted_at', 'desc')
            ->get();

        $banners = $this->feedReader->bannerPaths();

        return view('/accountant/feed/feed', [
            'feeds' => $feeds,
            'tags' => $this->feedReader->tagCloud($schoolId),
            'birthday' => $banners['birthday'],
            'anniversary' => $banners['anniversary'],
            'exam' => $banners['exam'],
        ]);
    }

    /**
     * school_id/academic_year_id/is_posted-scoped Post query, with the
     * `list`/`search` request filters applied on top. Accountant's
     * default (no `list`/`search` given) is `visibility = all_class`.
     */
    private function scopedFeedQuery(Request $request, int $schoolId, int $academicYearId): Builder
    {
        $query = Post::query();
        $this->feedReader->scopeToTenant($query, $schoolId, $academicYearId);

        if (! $this->feedReader->applyListOrSearchFilter($query, $request->list, $request->search, $schoolId)) {
            $query->where('visibility', 'all_class');
        }

        return $query;
    }

    /**
     * Filter posts by tag or visibility category and return the filtered view.
     *
     * @return View
     */
    public function filter(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $academicYearId = SiteHelper::getAcademicYear($schoolId)->id;

        $feeds = $this->scopedFeedQuery($request, $schoolId, $academicYearId)->get();

        $banners = $this->feedReader->bannerPaths();

        return view('/accountant/feed/filter', [
            'feeds' => $feeds,
            'tags' => $this->feedReader->tagCloud($schoolId),
            'birthday' => $banners['birthday'],
            'anniversary' => $banners['anniversary'],
            'exam' => $banners['exam'],
        ]);
    }
}
