<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Services;

use App\Http\Resources\backgroundImagesResource;
use App\Http\Resources\StandardLink as StandardLinkResource;
use App\Models\BackgroundImage;
use App\Models\NoticeBoard;
use App\Models\StandardLink;
use App\Models\Teacherlink;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class NoticeBoardReaderService
 *
 * Owns the read/display side of notice board queries shared by every
 * role dashboard (Admin, Teacher, Receptionist, Student, Accountant) and
 * the mobile app APIs (Parent App, Teacher App). Deliberately excludes
 * creation, updates, uploads, and push notifications - those stay in
 * Admin\NoticeBoardController, which owns the write side.
 *
 * A notice's audience is determined by two columns:
 * - `standardLink_id`: null for school-wide and teacher-only notices,
 *   set to a specific class for `type = 'class'` notices.
 * - `type`: 'school' | 'class' | 'teacher'. Teacher-only notices are
 *   structurally identical to school-wide ones (standardLink_id is null
 *   for both) - only `type` tells them apart.
 */
class NoticeBoardReaderService
{
    /**
     * Standard IDs a teacher is linked to: the classes they're the class
     * teacher for, plus any classes they teach a subject in.
     *
     * @return array<int>
     */
    public function standardLinksForTeacher(int $schoolId, int $academicYearId, int $teacherId): array
    {
        $classTeacherOf = StandardLink::where('school_id', $schoolId)
            ->where('academic_year_id', $academicYearId)
            ->where('class_teacher_id', $teacherId)
            ->pluck('id')
            ->toArray();

        $subjectTeacherOf = Teacherlink::where('school_id', $schoolId)
            ->where('academic_year_id', $academicYearId)
            ->where('teacher_id', $teacherId)
            ->pluck('standardLink_id')
            ->toArray();

        return array_values(array_unique(array_merge($classTeacherOf, $subjectTeacherOf)));
    }

    /**
     * Paginated, filterable listing for web dashboards.
     *
     * @param  array<int>|null  $standardLinkIds  null = unrestricted (Admin/Receptionist/Accountant see every class).
     *                                            array = restricted to these classes (plus school-wide, if
     *                                            $includeNullScope is true).
     */
    public function paginatedList(
        int $schoolId,
        int $academicYearId,
        ?array $standardLinkIds,
        bool $includeNullScope,
        bool $excludeTeacherType,
        bool $includeExpired = false,
        ?int $standardLinkFilter = null,
        ?string $search = null,
        int $perPage = 10
    ): LengthAwarePaginator {
        $query = $this->baseQuery($schoolId, $academicYearId, $includeExpired);

        $this->applyAudienceScope($query, $standardLinkIds, $includeNullScope, $excludeTeacherType);
        $this->applyRequestFilters($query, $standardLinkFilter, $search);

        return $query->paginate($perPage);
    }

    /**
     * Unpaginated active/expired listing for the mobile app APIs.
     *
     * @param  array<int>|null  $standardLinkIds  See paginatedList().
     */
    public function list(
        int $schoolId,
        int $academicYearId,
        ?array $standardLinkIds,
        bool $includeNullScope,
        bool $excludeTeacherType,
        bool $expired = false
    ): Collection {
        $query = NoticeBoard::where('school_id', $schoolId)
            ->where('academic_year_id', $academicYearId)
            ->where(function (Builder $q) use ($expired) {
                if ($expired) {
                    $q->where('status', 0)->where('expire_date', '<=', now());
                } else {
                    $q->where('status', 1)->where('expire_date', '>=', now());
                }
            });

        $this->applyAudienceScope($query, $standardLinkIds, $includeNullScope, $excludeTeacherType);

        return $query->get();
    }

    /**
     * Look up a single notice, scoped to a school so one tenant can never
     * fetch another tenant's notice by guessing its id.
     */
    public function find(int $id, int $schoolId, ?string $requireType = null, ?string $excludeType = null): ?NoticeBoard
    {
        $query = NoticeBoard::where('id', $id)->where('school_id', $schoolId);

        if ($requireType !== null) {
            $query->where('type', $requireType);
        }

        if ($excludeType !== null) {
            $query->where('type', '!=', $excludeType);
        }

        return $query->first();
    }

    /**
     * Dropdown data (classes + background images) for the web filter/create UI.
     */
    public function filterOptions(int $schoolId): array
    {
        $standardLink = StandardLink::with('standard', 'section')->where('school_id', $schoolId)->get();
        $backgroundimages = BackgroundImage::where('school_id', $schoolId)->latest()->get();

        return [
            'standardLinklist' => StandardLinkResource::collection($standardLink),
            'backgroundimages' => backgroundImagesResource::collection($backgroundimages),
        ];
    }

    /**
     * Active-by-default query, scoped to a single school/academic year and
     * never allowed to escape that scope - the pre-existing bug this
     * service replaces used a top-level orWhere() for the showExpired
     * case, which broke out of the school/year AND-grouping entirely.
     */
    protected function baseQuery(int $schoolId, int $academicYearId, bool $includeExpired): Builder
    {
        return NoticeBoard::where('school_id', $schoolId)
            ->where('academic_year_id', $academicYearId)
            ->where(function (Builder $q) use ($includeExpired) {
                $q->where(function (Builder $active) {
                    $active->where('status', 1)->where('expire_date', '>=', now());
                });

                if ($includeExpired) {
                    $q->orWhere(function (Builder $expired) {
                        $expired->where('status', 0)->where('expire_date', '<=', now());
                    });
                }
            });
    }

    /**
     * Restricts a query to the classes (plus optionally school-wide) a
     * viewer may see, and optionally excludes teacher-only notices.
     *
     * @param  array<int>|null  $standardLinkIds
     */
    protected function applyAudienceScope(Builder $query, ?array $standardLinkIds, bool $includeNullScope, bool $excludeTeacherType): void
    {
        if ($standardLinkIds !== null) {
            $query->where(function (Builder $q) use ($standardLinkIds, $includeNullScope) {
                $hasIds = ! empty($standardLinkIds);

                if ($hasIds) {
                    $q->whereIn('standardLink_id', $standardLinkIds);
                }

                if ($includeNullScope) {
                    $hasIds ? $q->orWhereNull('standardLink_id') : $q->whereNull('standardLink_id');
                }

                if (! $hasIds && ! $includeNullScope) {
                    $q->whereRaw('1 = 0');
                }
            });
        }

        if ($excludeTeacherType) {
            $query->where('type', '!=', 'teacher');
        }
    }

    protected function applyRequestFilters(Builder $query, ?int $standardLinkId, ?string $search): void
    {
        if (! empty($standardLinkId)) {
            $query->where('standardLink_id', $standardLinkId);
        }

        if (! empty($search)) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
    }
}
