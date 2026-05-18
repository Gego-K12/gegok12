<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models\Users;

use App\Models\User;

/**
 * Class TeacherUser
 *
 * Specialized User model for teacher-specific functionality.
 * Extends the base User model with teacher-focused relationships and scopes.
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TeacherProfile[] $teacherprofile
 * @property-read \App\Models\TeacherProfile $latestTeacherProfile
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Teacherlink[] $teacherlink
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attendance[] $attendanceAdmin
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Assignment[] $assignment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StudentAssignment[] $teacherAssignment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TeacherLeaveApplication[] $requestedUser
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TeacherLeaveApplication[] $approvedUser
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Discipline[] $disciplineTeacher
 * @property-read \App\Models\StandardLink $standardLink
 * @mixin \Eloquent
 */
class TeacherUser extends User
{
    /**
     * Scope to filter teachers by qualification.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $qualification
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByQualification($query, $qualification)
    {
        return $query->wherehas('teacherprofile', function ($query) use ($qualification) {
            $query->where('qualification_id', '=', $qualification);
        });
    }

    /**
     * Scope to filter teachers by designation.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $designation
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDesignation($query, $designation)
    {
        return $query->wherehas('teacherprofile', function ($query) use ($designation) {
            $query->where('designation', 'LIKE', $designation);
        });
    }

    /**
     * Scope to filter teachers by job type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $job_type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByJobType($query, $job_type)
    {
        return $query->wherehas('teacherprofile', function ($query) use ($job_type) {
            $query->where('job_type', $job_type);
        });
    }

    /**
     * Scope to filter users by employee ID.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $employeeId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByEmployeeId($query, $employeeId)
    {
        return $query->whereHas('latestTeacherProfile', function ($q) use ($employeeId) {
            $q->where('employee_id', $employeeId);
        });
    }

    /**
     * Get teacher details for this user.
     *
     * @return array
     */
    public function getTeacherDetails()
    {
        $array = [];
        $i = 0;
        foreach ($this->teacherprofile as $teacher) {
            $array['designation'] = $teacher->designation;
            $array['designation_name'] = str_replace('_', ' ', ucwords($teacher->designation));
            $array['reporting_to'] = $teacher->reporting_to;
            $array['sub_designation'] = $teacher->sub_designation;
            $array['employee_id'] = $teacher->employee_id;
            $array['ug_degree'] = $teacher->ug_degree;
            $array['ug_degree_name'] = $teacher->ug_degree ? \App\Models\Qualification::find($teacher->ug_degree)?->display_name : null;
            $array['pg_degree'] = $teacher->pg_degree;
            $array['pg_degree_name'] = $teacher->pg_degree ? \App\Models\Qualification::find($teacher->pg_degree)?->display_name : null;
            $array['specialization'] = $teacher->specialization;
            $array['job_type'] = $teacher->job_type;
            $array['interested_in'] = $teacher->interested_in;
            $array['sub_qualification'] = $teacher->sub_qualification;
            $array['status'] = $teacher->status;
            $array['qualification_id'][$i]['qualification_id'] = $teacher->qualification_id;
            $array['qualification_name'][$i] = $teacher->qualification->display_name;
            $i++;
        }
        return $array;
    }

    /**
     * Get count of approved leave applications.
     *
     * @return int
     */
    public function getLeaveCountAttribute()
    {
        return $this->requestedUser->where('status', 'approved')->count();
    }
}
