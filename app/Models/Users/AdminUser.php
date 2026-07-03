<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models\Users;

use App\Models\Attendance;
use App\Models\LessonPlanApproval;
use App\Models\School;
use App\Models\TeacherLeaveApplication;
use App\Models\User;
use App\Models\Userprofile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AdminUser
 *
 * Specialized User model for school admin functionality.
 * School/Premium admins (usergroups 3, 4) have access to school-level administrative features.
 *
 * @property-read School $school
 * @property-read Userprofile $userprofile
 * @property-read Collection|Attendance[] $attendanceAdmin
 * @property-read Collection|TeacherLeaveApplication[] $approvedUser
 * @property-read Collection|LessonPlanApproval[] $approvedLessonPlan
 *
 * @mixin \Eloquent
 */
class AdminUser extends User
{
    /**
     * Scope to filter school admins only.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeSchoolAdminsOnly($query)
    {
        return $query->whereIn('usergroup_id', [
            self::SCHOOLADMIN_USERGROUP_ID,
            self::SCHOOLSUBADMIN_USERGROUP_ID,
        ]);
    }

    /**
     * Scope to filter admins by school.
     *
     * @param  Builder  $query
     * @param  int  $school_id
     * @return Builder
     */
    public function scopeForSchool($query, $school_id)
    {
        return $query->where('school_id', $school_id)
            ->whereIn('usergroup_id', [
                self::SCHOOLADMIN_USERGROUP_ID,
                self::SCHOOLSUBADMIN_USERGROUP_ID,
            ]);
    }

    /**
     * Check if this admin is primary school admin.
     */
    public function isPrimaryAdmin(): bool
    {
        return $this->usergroup_id === self::SCHOOLADMIN_USERGROUP_ID;
    }

    /**
     * Check if this admin is sub admin.
     */
    public function isSubAdmin(): bool
    {
        return $this->usergroup_id === self::SCHOOLSUBADMIN_USERGROUP_ID;
    }

    /**
     * Get all staff members under this school admin.
     *
     * @return Collection
     */
    public function getSchoolStaff()
    {
        $staffUsergroups = [
            User::TEACHER_USERGROUP_ID,
            User::LIBRARIAN_USERGROUP_ID,
            User::RECEPTIONIST_USERGROUP_ID,
            User::ACCOUNTANT_USERGROUP_ID,
            User::STOCK_KEEPER_USERGROUP_ID,
            User::NON_TEACHING_USERGROUP_ID,
        ];

        return User::where('school_id', $this->school_id)
            ->whereIn('usergroup_id', $staffUsergroups)
            ->get();
    }

    /**
     * Get all students in this school.
     *
     * @return Collection
     */
    public function getSchoolStudents()
    {
        return User::where('school_id', $this->school_id)
            ->where('usergroup_id', User::STUDENT_USERGROUP_ID)
            ->get();
    }

    /**
     * Get all parents in this school.
     *
     * @return Collection
     */
    public function getSchoolParents()
    {
        return User::where('school_id', $this->school_id)
            ->where('usergroup_id', User::PARENT_USERGROUP_ID)
            ->get();
    }

    /**
     * Get all teachers in this school.
     *
     * @return Collection
     */
    public function getSchoolTeachers()
    {
        return User::where('school_id', $this->school_id)
            ->where('usergroup_id', User::TEACHER_USERGROUP_ID)
            ->get();
    }
}
