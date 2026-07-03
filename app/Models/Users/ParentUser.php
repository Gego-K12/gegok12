<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models\Users;

use App\Models\Feedback;
use App\Models\ParentProfile;
use App\Models\StudentParentLink;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ParentUser
 *
 * Specialized User model for parent-specific functionality.
 * Extends the base User model with parent-focused relationships and scopes.
 *
 * @property-read Collection|ParentProfile[] $parentprofile
 * @property-read Collection|StudentParentLink[] $children
 * @property-read Collection|Feedback[] $feedbackParent
 *
 * @mixin \Eloquent
 */
class ParentUser extends User
{
    /**
     * Scope to filter parents by first name.
     *
     * @param  Builder  $query
     * @param  string  $firstname
     * @return Builder
     */
    public function scopeByFirstName($query, $firstname)
    {
        return $query->where('usergroup_id', self::PARENT_USERGROUP_ID)
            ->wherehas('parents', function ($query) use ($firstname) {
                $query->whereHas('userParent', function ($q) use ($firstname) {
                    $q->whereHas('userprofile', function ($qu) use ($firstname) {
                        $qu->where('firstname', 'LIKE', $firstname.'%');
                    });
                });
            });
    }

    /**
     * Scope to filter parents by full name.
     *
     * @param  Builder  $query
     * @param  string  $fullname
     * @return Builder
     */
    public function scopeByFullName($query, $fullname)
    {
        return $query->whereHas('userprofile', function ($qu) use ($fullname) {
            $qu->where('firstname', 'LIKE', $fullname.'%');
        });
    }

    /**
     * Scope to filter parents by last name.
     *
     * @param  Builder  $query
     * @param  string  $lastname
     * @return Builder
     */
    public function scopeByLastName($query, $lastname)
    {
        return $query->where('usergroup_id', self::PARENT_USERGROUP_ID)
            ->wherehas('parents', function ($query) use ($lastname) {
                $query->whereHas('userParent', function ($q) use ($lastname) {
                    $q->whereHas('userprofile', function ($qu) use ($lastname) {
                        $qu->where('lastname', 'LIKE', $lastname.'%');
                    });
                });
            });
    }

    /**
     * Scope to filter parents by mobile number.
     *
     * @param  Builder  $query
     * @param  string  $mobile_no
     * @return Builder
     */
    public function scopeByMobileNo($query, $mobile_no)
    {
        return $query->where('mobile_no', 'LIKE', $mobile_no.'%');
    }

    /**
     * Scope to filter parents by email.
     *
     * @param  Builder  $query
     * @param  string  $email
     * @return Builder
     */
    public function scopeByEmail($query, $email)
    {
        return $query->where('usergroup_id', self::PARENT_USERGROUP_ID)
            ->wherehas('parents', function ($query) use ($email) {
                $query->whereHas('userParent', function ($q) use ($email) {
                    $q->where('email', $email);
                });
            });
    }

    /**
     * Scope to filter parents by qualification.
     *
     * @param  Builder  $query
     * @param  int  $qualification
     * @return Builder
     */
    public function scopeByQualification($query, $qualification)
    {
        return $query->where('usergroup_id', self::PARENT_USERGROUP_ID)
            ->wherehas('parents', function ($query) use ($qualification) {
                $query->whereHas('userParent', function ($q) use ($qualification) {
                    $q->whereHas('parentprofile', function ($qu) use ($qualification) {
                        $qu->where('qualification_id', '=', $qualification);
                    });
                });
            });
    }

    /**
     * Scope to filter parents by occupation.
     *
     * @param  Builder  $query
     * @param  string  $occupation
     * @return Builder
     */
    public function scopeByOccupation($query, $occupation)
    {
        return $query->where('usergroup_id', self::PARENT_USERGROUP_ID)
            ->wherehas('parents', function ($query) use ($occupation) {
                $query->whereHas('userParent', function ($q) use ($occupation) {
                    $q->whereHas('parentprofile', function ($qu) use ($occupation) {
                        $qu->where('profession', 'LIKE', $occupation);
                    });
                });
            });
    }

    /**
     * Scope to filter parents by student's standard/grade.
     *
     * @param  Builder  $query
     * @param  int  $standardlink_id
     * @return Builder
     */
    public function scopeByStudentStandard($query, $standardlink_id)
    {
        return $query->where('usergroup_id', self::PARENT_USERGROUP_ID)
            ->wherehas('parents', function ($query) use ($standardlink_id) {
                $query->whereHas('userParent', function ($quer) use ($standardlink_id) {
                    $quer->whereHas('children', function ($que) use ($standardlink_id) {
                        $que->whereHas('userStudent', function ($qu) use ($standardlink_id) {
                            $qu->whereHas('studentAcademicLatest', function ($q) use ($standardlink_id) {
                                $q->where('standardlink_id', $standardlink_id);
                            });
                        });
                    });
                });
            });
    }

    /**
     * Scope to filter parents by student name.
     *
     * @param  Builder  $query
     * @param  string  $student_name
     * @return Builder
     */
    public function scopeByStudentName($query, $student_name)
    {
        return $query->whereHas('children', function ($que) use ($student_name) {
            $que->whereHas('userStudent', function ($qu) use ($student_name) {
                $qu->whereHas('userprofile', function ($q) use ($student_name) {
                    $q->where('firstname', 'LIKE', $student_name.'%');
                });
            });
        });
    }

    /**
     * Get parent details for this user.
     *
     * @return array
     */
    public function getParentDetails()
    {
        $array = [];
        $i = 0;
        foreach ($this->parentprofile as $parent) {
            $array['profession'] = $parent->profession;
            $array['sub_occupation'] = $parent->sub_occupation;
            $array['designation'] = $parent->designation;
            $array['organization_name'] = $parent->organization_name;
            $array['official_address'] = $parent->official_address;
            $array['relation'] = $parent->relation;
            $array['annual_income'] = $parent->annual_income;
            $array['qualification_id'][$i]['qualification_id'] = $parent->qualification_id;
            $array['qualification_name'][$i] = $parent->qualification->display_name;
            $i++;
        }

        return $array;
    }

    /**
     * Get children names in formatted string.
     *
     * @return string
     */
    public function getChildren()
    {
        $i = 0;
        $array = [];
        foreach ($this->children as $children) {
            $data[] = $children->userStudent->FullName.' ('.$children->userStudent->studentAcademicLatest->standardLink->StandardSection.')';
            $i++;
        }
        $child = implode(', ', $data);

        return $child;
    }
}
