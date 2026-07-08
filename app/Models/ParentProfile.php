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
 * Class ParentProfile
 *
 * Model for managing parent user profile details.
 *
 * @property int $id
 * @property int $school_id
 * @property int $user_id
 * @property int|null $qualification_id
 * @property string|null $profession
 * @property string|null $sub_occupation
 * @property string|null $designation
 * @property string|null $organization_name
 * @property string|null $official_address
 * @property string|null $relation
 * @property float|null $annual_income
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read School $school
 * @property-read User $user
 * @property-read Qualification $qualification
 *
 * @mixin \Eloquent
 */
class ParentProfile extends Model
{
    use HasFactory;
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parent_profiles';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'user_id', 'qualification_id', 'profession', 'sub_occupation', 'designation', 'organization_name', 'official_address', 'relation', 'annual_income',
    ];

    /**
     * Get the school for this parent profile.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id');
    }

    /**
     * Get the user for this parent profile.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * Get the qualification for this parent.
     *
     * @return BelongsTo
     */
    public function qualification()
    {
        return $this->belongsTo('\App\Models\Qualification', 'qualification_id');
    }
}
