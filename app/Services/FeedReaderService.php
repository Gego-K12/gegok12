<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Services;

use App\Models\PostTag;
use App\Models\Tag;
use App\Traits\Common;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class FeedReaderService
 *
 * Owns the query-building shared by all 5 FeedController copies
 * (Admin/Accountant/Receptionist/Student/Teacher classwall feed).
 *
 * Fixes a real cross-tenant leak: none of the 5 copies ever scoped their
 * Post queries by school_id - unlike the sibling PostsController::list(),
 * which correctly scopes by school_id + academic_year_id + is_posted for
 * the exact same underlying data. Any authenticated user in any school
 * could see every other school's classwall posts via /feeds.
 *
 * Also fixes a second, unrelated bug: every filter()'s tag cloud joined a
 * table named `post_tag` (singular), which doesn't exist - the real table
 * is `post_tags` (plural, matching what index() already used correctly
 * and what the post_tags migration actually creates). filter() threw a
 * SQL error on every call because of this.
 */
class FeedReaderService
{
    use Common;

    /**
     * school_id + academic_year_id + is_posted, matching the reference
     * scoping already used correctly by PostsController::list() for the
     * same Post data.
     */
    public function scopeToTenant(Builder $query, int $schoolId, int $academicYearId): Builder
    {
        return $query
            ->where('school_id', $schoolId)
            ->where('academic_year_id', $academicYearId)
            ->where('is_posted', 1);
    }

    /**
     * Applies the `list` (visibility) or `search` (tag name) filter from
     * the request onto the query, if either was given. Returns whether a
     * filter was applied, so the caller knows whether to fall back to its
     * own default scope.
     */
    public function applyListOrSearchFilter(Builder $query, ?string $listFilter, ?string $searchFilter, int $schoolId): bool
    {
        if ($listFilter) {
            $query->where('visibility', $listFilter);

            return true;
        }

        if ($searchFilter) {
            $query->whereIn('id', $this->postIdsMatchingTag($searchFilter, $schoolId));

            return true;
        }

        return false;
    }

    /**
     * @return array<int>
     */
    public function postIdsMatchingTag(string $tagName, int $schoolId): array
    {
        $tag = Tag::where('tag_name', $tagName)->first();

        if (! $tag) {
            return [];
        }

        return PostTag::join('posts', 'post_tags.post_id', '=', 'posts.id')
            ->where('post_tags.tag_id', $tag->id)
            ->where('posts.school_id', $schoolId)
            ->pluck('post_tags.post_id')
            ->toArray();
    }

    /**
     * Top 20 most-used tags, scoped to the given school so the sidebar
     * tag cloud doesn't leak other schools' tag usage counts.
     */
    public function tagCloud(int $schoolId): Collection
    {
        return Tag::join('post_tags', 'tags.id', '=', 'post_tags.tag_id')
            ->join('posts', 'post_tags.post_id', '=', 'posts.id')
            ->where('posts.school_id', $schoolId)
            ->groupBy('tags.id')
            ->select(['tags.*', DB::raw('COUNT(*) as cnt')])
            ->orderBy('cnt', 'desc')
            ->take(20)
            ->get();
    }

    /**
     * @return array{birthday: string, anniversary: string, exam: string}
     */
    public function bannerPaths(): array
    {
        return [
            'birthday' => $this->getFilePath('uploads/images/birthday.jpg'),
            'anniversary' => $this->getFilePath('uploads/images/work_anniversary.jpg'),
            'exam' => $this->getFilePath('uploads/images/exam-banner.jpg'),
        ];
    }
}
