<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class StudentAssignment
 *
 * Model for managing student assignment submissions and grades.
 *
 * @property int $id
 * @property int $assignment_id
 * @property int $user_id
 * @property array $assignment_file
 * @property float $obtained_marks
 * @property \DateTime $submitted_on
 * @property string $comments
 * @property int $marks_given_by
 * @property \DateTime $marks_given_on
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property string $assignmentFilePath
 * @property-read Assignment $assignment
 * @property-read User $student
 * @property-read User $teacher
 *
 * @mixin \Eloquent
 */
class StudentAssignment extends Model
{
    use Common;
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'student_assignments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'assignment_id', 'user_id', 'assignment_file', 'obtained_marks', 'submitted_on', 'comments', 'marks_given_by', 'marks_given_on', 'status',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['submitted_on', 'marks_given_on', 'deleted_at'];

    protected $casts = ['assignment_file' => 'array'];

    /**
     * Get the assignment for this submission.
     *
     * @return BelongsTo
     */
    public function assignment()
    {
        return $this->belongsTo('App\Models\Assignment', 'assignment_id');
    }

    /**
     * Get the student who submitted this assignment.
     *
     * @return BelongsTo
     */
    public function student()
    {
        return $this->belongsTo('\App\Models\User', 'user_id');
    }

    /**
     * Get the teacher who graded this assignment.
     *
     * @return BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo('\App\Models\User', 'marks_given_by');
    }

    /**
     * Get the assignment file paths.
     *
     * @return array
     */
    public function getAssignmentFilePathAttribute()
    {
        if (! $this->assignment_file || ! is_array($this->assignment_file)) {
            return [];
        }

        $attachment = [];

        foreach ($this->assignment_file as $index => $file) {

            $attachment[] = [
                'id' => $index,
                'original_path' => $file,
                'path' => $this->getFilePath($file),
            ];
        }

        return $attachment;
    }
}
