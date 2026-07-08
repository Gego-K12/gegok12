<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models\Users;

use App\Models\Attendance;
use App\Models\BookLending;
use App\Models\Discipline;
use App\Models\Mark;
use App\Models\Promotion;
use App\Models\RouteStudent;
use App\Models\StudentAcademic;
use App\Models\StudentAssignment;
use App\Models\StudentParentLink;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Tags\HasTags;

/**
 * Class StudentUser
 *
 * Specialized User model for student-specific functionality.
 * Extends the base User model with student-focused relationships and scopes.
 *
 * @property-read Collection|StudentAcademic[] $studentAcademic
 * @property-read StudentAcademic $studentAcademicLatest
 * @property-read Collection|Mark[] $marks
 * @property-read Collection|StudentAssignment[] $studentAssignment
 * @property-read Collection|StudentParentLink[] $parents
 * @property-read Collection|StudentParentLink[] $children
 * @property-read Collection|Discipline[] $disciplineUser
 * @property-read Collection|Attendance[] $attendanceUser
 * @property-read Collection|BookLending[] $lending
 * @property-read Collection|Promotion[] $promotion
 * @property-read RouteStudent $routeStudent
 *
 * @mixin \Eloquent
 */
class StudentUser extends User
{
    use HasTags;

    /**
     * Scope to filter students by standard/grade.
     *
     * @param  Builder  $query
     * @param  int  $standard
     * @return Builder
     */
    public function scopeByStandard($query, $standard)
    {
        return $query->wherehas('studentAcademic', function ($query) use ($standard) {
            $query->wherehas('standardLink', function ($query) use ($standard) {
                $query->where('id', '=', $standard);
            });
        });
    }

    /**
     * Scope to filter students by mode of transport.
     *
     * @param  Builder  $query
     * @param  string  $transport
     * @return Builder
     */
    public function scopeByTransport($query, $transport)
    {
        return $query->wherehas('studentAcademic', function ($query) use ($transport) {
            $query->where('mode_of_transport', '=', $transport);
        });
    }

    /**
     * Scope to filter students by admission/registration number.
     *
     * @param  Builder  $query
     * @param  string  $admission_number
     * @return Builder
     */
    public function scopeByAdmissionNumber($query, $admission_number)
    {
        return $query->where('registration_number', 'LIKE', $admission_number.'%');
    }

    /**
     * Get children names in formatted string.
     *
     * @return string
     */
    public function getChildren()
    {
        $data = [];
        foreach ($this->children as $child) {
            $data[] = $child->userStudent->FullName.' ('.$child->userStudent->studentAcademicLatest->standardLink->StandardSection.')';
        }

        return implode(', ', $data);
    }

    /**
     * Scope to filter students by tag.
     *
     * @param  Builder  $query
     * @param  string  $tag
     * @return Builder
     */
    public function scopeByStudentTag($query, $tag)
    {

        return $query->withAnyTags([$tag], 'student');
    }
}
