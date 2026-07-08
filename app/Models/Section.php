<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Section
 *
 * Model for managing school sections/divisions.
 *
 * @property int $id
 * @property int $school_id
 * @property string $name
 * @property string $value
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read School $school
 * @property-read Promotion $currentPromotion
 * @property-read Promotion $nextPromotion
 * @property-read Collection|Subject[] $subject
 * @property-read Collection|StandardLink[] $standardLink
 *
 * @mixin \Eloquent
 */
class Section extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'name', 'value', 'status',
    ];

    /**
     * Get the school for this section.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('\App\Models\School', 'school_id');
    }

    /**
     * Get the current promotion for this section.
     *
     * @return BelongsTo
     */
    public function currentPromotion()
    {
        return $this->belongsTo('App\Models\Promotion', 'section_id', 'id');
    }

    /**
     * Get the next promotion for this section.
     *
     * @return BelongsTo
     */
    public function nextPromotion()
    {
        return $this->belongsTo('App\Models\Promotion', 'section_id', 'id');
    }

    /**
     * Get subjects in this section.
     *
     * @return HasMany
     */
    public function subject()
    {
        return $this->hasMany('\App\Models\Subject', 'section_id', 'id');
    }

    /**
     * Get standard links for this section.
     *
     * @return HasMany
     */
    public function standardLink()
    {
        return $this->hasMany('App\Models\StandardLink', 'section_id', 'id');
    }
}
