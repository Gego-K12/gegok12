<?php
// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Class Task
 *
 * Model for managing user tasks with reminders and assignments.
 *
 * @property int $id
 * @property int $school_id
 * @property int $academic_year_id
 * @property int $user_id
 * @property string $title
 * @property string $type
 * @property \DateTime $task_date
 * @property string $reminder
 * @property \DateTime $reminder_date
 * @property string $to_do_list
 * @property int $task_status
 * @property int $task_flag
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property int $todayTask
 * @property int $overDueTask
 * @property int $upcomingTask
 * @property string $reminderValue
 * @property string $flag
 * @property-read \App\Models\School $school
 * @property-read \App\Models\AcademicYear $academicYear
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TaskAssignee[] $taskAssignee
 * @mixin \Eloquent
 */
class Task extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'task';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id' , 'academic_year_id' , 'user_id' , 'title' , 'type', 'task_date' , 'reminder' , 'reminder_date' , 'to_do_list' , 'task_status' , 'task_flag' , 'priority' , 'task_type'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    //protected $dates = ['task_date' , 'reminder_date'];

    protected $casts = [
        'task_date' => 'datetime',
        'reminder_date' => 'datetime',
    ];

    /**
     * Get the school for this task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School','school_id');
    }

    /**
     * Get the academic year for this task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function academicYear()
    {
        return $this->belongsTo('\App\Models\AcademicYear','academic_year_id');
    }

    /**
     * Get users assigned to this task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
    	return $this->hasMany('\App\Models\User','id','user_id');
    }

    /**
     * Get count of today tasks.
     *
     * @return int
     */
    public function getTodayTaskAttribute()
    {
        return Task::where('task_flag',1)->where('task_status',0)->count();
    }

    /**
     * Get count of overdue tasks.
     *
     * @return int
     */
    public function getOverDueTaskAttribute()
    {
        return Task::where('task_flag',0)->where('task_status',0)->count();
    }

    /**
     * Get count of upcoming tasks.
     *
     * @return int
     */
    public function getUpcomingTaskAttribute()
    {
        return Task::where('task_flag',2)->where('task_status',0)->count();
    }

    /**
     * Get task assignees for this task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taskAssignee()
    {
        return $this->hasMany('\App\Models\TaskAssignee','task_id','id');
    }

    /**
     * Get the calculated reminder value based on reminder setting.
     *
     * @return string
     */
    public function getReminderValueAttribute()
    {
        $task_date = date('Y-m-d H:i:s',strtotime($this->task_date->format('Y-m-d H:i:s')));
        if($this->reminder == 'one_hour_before_the_task')
        {
            $reminder_date = Carbon::parse($task_date)->subHours(1)->format('Y-m-d H:i:s');
        }
        elseif($this->reminder == 'one_day_before_the_task')
        {
            $reminder_date = Carbon::parse($task_date)->subDays(1)->format('Y-m-d H:i:s');
        }
        elseif($this->reminder == 'two_days_before_the_task')
        {
            $reminder_date = Carbon::parse($task_date)->subDays(2)->format('Y-m-d H:i:s');
        }

        return $reminder_date;
    }

    /**
     * Scope to filter tasks by status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query,$status)
    {
        $query->where('task_status',$status);

        return $query;
    }

    /**
     * Scope to filter tasks by type (by_me or assigned).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @param int $user_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByType($query,$type,$user_id)
    {
        if($type == 'by_me')
        {
            $query->where('user_id',$user_id);
        }
        else
        {
            $query->whereHas('taskAssignee', function ($q) use($user_id){
                $q->where('user_id',$user_id);
            });
        }

        return $query;
    }

    /**
     * Get the flag label for this task.
     *
     * @return string
     */
    public function getFlagAttribute()
    {
        if($this->task_flag == 0)
        {
            $task_flag = 'Overdue';
        }
        elseif($this->task_flag == 1)
        {
            $task_flag = 'Today';
        }
        else
        {
            $task_flag = 'Upcoming';
        }

        return $task_flag;
    }
}
