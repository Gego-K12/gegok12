<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Models\Users;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Tags\HasTags;

/**
 * Class StudentUser
 *
 * Specialized User model for student-specific functionality.
 * Extends the base User model with student-focused relationships and scopes.
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StudentAcademic[] $studentAcademic
 * @property-read \App\Models\StudentAcademic $studentAcademicLatest
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mark[] $marks
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StudentAssignment[] $studentAssignment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StudentParentLink[] $parents
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StudentParentLink[] $children
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Discipline[] $disciplineUser
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attendance[] $attendanceUser
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BookLending[] $lending
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Promotion[] $promotion
 * @property-read \App\Models\RouteStudent $routeStudent
 * @mixin \Eloquent
 */
class StudentUser extends User
{
    use HasTags;
    /**
     * Scope to filter students by standard/grade.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $standard
     * @return \Illuminate\Database\Eloquent\Builder
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
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $transport
     * @return \Illuminate\Database\Eloquent\Builder
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
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $admission_number
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByAdmissionNumber($query, $admission_number)
    {
        return $query->where('registration_number', 'LIKE', $admission_number . '%');
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
            $data[] = $child->userStudent->FullName . ' (' . $child->userStudent->studentAcademicLatest->standardLink->StandardSection . ')';
        }
        return implode(', ', $data);
    }
    /**
     * Scope to filter students by tag.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $tag
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStudentTag($query, $tag)
    {

         return $query->withAnyTags([$tag], 'student');
    }
}
