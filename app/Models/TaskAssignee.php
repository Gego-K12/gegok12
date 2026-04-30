<?php
// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TaskAssignee
 *
 * Model for managing task assignments to users or groups.
 *
 * @property int $id
 * @property int $task_id
 * @property int $user_id
 * @property int $standardLink_id
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Task $task
 * @property-read \App\Models\StandardLink $standardLink
 * @mixin \Eloquent
 */
class TaskAssignee extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'task_assignees';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_id' , 'user_id' , 'standardLink_id' , 'status' , 'assigned_type'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the user assigned to this task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo('\App\Models\User','user_id');
    }

    /**
     * Get the task for this assignment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
    	return $this->belongsTo('\App\Models\Task','task_id');
    }

    /**
     * Get the standard link for this assignment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function standardLink()
    {
    	return $this->belongsTo('\App\Models\StandardLink','standardLink_id');
    }
}
