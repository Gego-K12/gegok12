<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class StudentParentLink
 *
 * Model for managing relationships between students and parents.
 *
 * @property int $id
 * @property int $school_id
 * @property int $parent_id
 * @property int $student_id
 * @property string $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read User $userParent
 * @property-read User $userStudent
 *
 * @mixin \Eloquent
 */
class StudentParentLink extends Model
{
    use HasFactory;
    //
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'student_parent_links';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'parent_id', 'student_id', 'status',
    ];

    /**
     * Get the parent user for this relationship.
     *
     * @return BelongsTo
     */
    public function userParent()
    {
        return $this->belongsTo('App\Models\Users\ParentUser', 'parent_id')->where('usergroup_id', 7);
    }

    /**
     * Get the student user for this relationship.
     *
     * @return BelongsTo
     */
    public function userStudent()
    {
        return $this->belongsTo('App\Models\User', 'student_id')->where('usergroup_id', 6);
    }
}
