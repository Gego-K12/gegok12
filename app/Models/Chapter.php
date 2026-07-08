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
 * Class Chapter
 *
 * Model for managing chapters/topics in curriculum.
 *
 * @property int $id
 * @property int $school_id
 * @property int $standard_id
 * @property int $subject_id
 * @property string $name
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read School $school
 * @property-read StandardLink $standardLink
 * @property-read Subject $subject
 * @property-read Collection|QuizQuestion[] $questions
 *
 * @mixin \Eloquent
 */
class Chapter extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',   'standard_id',  'subject_id', ' name',
    ];

    /**
     * Get the school for this chapter.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('\App\Models\School', 'school_id');
    }

    /**
     * Get the standard link for this chapter.
     *
     * @return BelongsTo
     */
    public function standardLink()
    {
        return $this->belongsTo('\App\Models\StandardLink', 'standard_id');
    }

    /**
     * Get the subject for this chapter.
     *
     * @return BelongsTo
     */
    public function subject()
    {
        return $this->belongsTo('\App\Models\Subject', 'subject_id');
    }

    /**
     * Get the quiz questions for this chapter.
     *
     * @return HasMany
     */
    public function questions()
    {
        return $this->hasMany('\App\Models\QuizQuestion', 'chapter_id', 'id');
    }
}
