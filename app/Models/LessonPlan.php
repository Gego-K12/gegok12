<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
//use Laravel\Scout\Searchable;

/**
 * Class LessonPlan
 *
 * Model for managing lesson plans created by teachers.
 *
 * @property int $id
 * @property int $teacher_link_id
 * @property int $unit_no
 * @property string $unit_name
 * @property string $description
 * @property string $title
 * @property int $duration
 * @property string $objective
 * @property string $materials_required
 * @property string $introduction
 * @property string $procedure
 * @property string $conclusion
 * @property string $notes
 * @property string $assessment
 * @property string $modification
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read \App\Models\Teacherlink $teacherlink
 * @property-read \App\Models\LessonPlanApproval $lessonPlanApproval
 * @mixin \Eloquent
 */
class LessonPlan extends Model
{
    //
    use SoftDeletes;
    //use Searchable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lesson_plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'teacher_link_id' , 'unit_no' , 'unit_name' , 'description' , 'title' , 'duration' , 'objective' , 'materials_required' , 'introduction' , 'procedure' , 'conclusion' , 'notes' , 'assessment' , 'modification' , 'status', 'is_published', 'published_at' ,'start_date' , 'end_date'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'lesson_plans';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Customize array...
        //return array('id' => $array['id'], 'teacher_link_id' => $array['teacher_link_id'], 'title' => $array['title'], 'unit_name' => $array['unit_name']);
        return $array;
    }

    /**
     * Get the lesson plan approval for this lesson plan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lessonPlanApproval()
    {
        return $this->hasOne('\App\Models\LessonPlanApproval','lesson_plan_id','id');
    }

    /**
     * Get the teacher link associated with this lesson plan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teacherlink()
    {
        return $this->belongsTo('\App\Models\Teacherlink','teacher_link_id');
    }
}
