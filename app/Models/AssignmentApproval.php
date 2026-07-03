<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

/**
 * Class AssignmentApproval
 *
 * Model for assignment approvals.
 *
 * @property int $id
 * @property int $assignment_id
 * @property string $comments
 * @property int $status
 * @property int $approved_by
 * @property \Carbon\Carbon $approved_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\Models\User $approvedBy
 * @property-read \App\Models\Assignment $assignment
 *
 * @mixin \Eloquent
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignmentApproval extends Model
{
    use HasFactory;
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assignment_approvals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'assignment_id', 'comments', 'status', 'approved_by', 'approved_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['approved_at', 'deleted_at'];

    /**
     * Get the user who approved this assignment.
     *
     * @return BelongsTo
     */
    public function approvedBy()
    {
        return $this->belongsTo('\App\Models\User', 'approved_by');
    }

    /**
     * Get the assignment for this approval.
     *
     * @return BelongsTo
     */
    public function assignment()
    {
        return $this->belongsTo('\App\Models\Assignment', 'assignment_id');
    }
}
