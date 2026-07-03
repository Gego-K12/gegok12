<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Qualification
 *
 * Model for managing educational qualifications.
 *
 * @property int $id
 * @property string $display_name
 * @property string $type
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read Collection|ParentProfile[] $parentprofile
 * @property-read Collection|TeacherProfile[] $teacherprofile
 * @property-read Collection|TeacherProfile[] $ugDegree
 * @property-read Collection|TeacherProfile[] $pgDegree
 *
 * @mixin \Eloquent
 */
class Qualification extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'qualifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'display_name', 'type', 'status',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get parent profiles with this qualification.
     *
     * @return HasMany
     */
    public function parentprofile()
    {
        return $this->hasMany('App\Models\ParentProfile', 'qualification_id', 'id');
    }

    /**
     * Get teacher profiles with this qualification.
     *
     * @return HasMany
     */
    public function teacherprofile()
    {
        return $this->hasMany('App\Models\TeacherProfile', 'qualification_id', 'id');
    }

    /**
     * Get teacher profiles with this as undergraduate degree.
     *
     * @return HasMany
     */
    public function ugDegree()
    {
        return $this->hasMany('App\Models\TeacherProfile', 'ug_degree', 'id');
    }

    /**
     * Get teacher profiles with this as postgraduate degree.
     *
     * @return HasMany
     */
    public function pgDegree()
    {
        return $this->hasMany('App\Models\TeacherProfile', 'pg_degree', 'id');
    }
}
