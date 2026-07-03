<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class StudentHistory
 *
 * Model for tracking student activity and history.
 *
 * @property int $id
 * @property int $school_id
 * @property int $student_id
 * @property int|null $parent_id
 * @property \DateTime|null $read_at
 * @property int $entity_id
 * @property string $entity_type
 * @property string $type
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read User $user
 * @property-read User|null $parent
 *
 * @mixin \Eloquent
 */
class StudentHistory extends Model
{
    use SoftDeletes;

    protected $table = 'student_history';

    protected $fillable = ['school_id', 'student_id', 'parent_id', 'read_at', 'entity_id', 'entity_type', 'type'];

    /**
     * Get the polymorphic entity associated with this history record.
     *
     * @return MorphTo
     */
    public function entity()
    {
        return $this->morphTo();
    }

    /**
     * Get the parent user associated with this history.
     *
     * @return BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('\App\Models\User', 'parent_id');
    }

    /**
     * Get the student user associated with this history.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('\App\Models\User', 'student_id');
    }
}
