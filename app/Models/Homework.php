<?php
// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Helpers\SiteHelper;
use App\Traits\Common;

/**
 * Class Homework
 *
 * Model for managing homework assignments.
 *
 * @property int $id
 * @property int $school_id
 * @property int $academic_year_id
 * @property int $standardLink_id
 * @property int $section_id
 * @property int $subject_id
 * @property string $description
 * @property string|null $attachment
 * @property \DateTime $date
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $teacher_id
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property string $attachment_path
 * @property string $attachment_type
 * @property int $finished_count
 * @property int $pending_count
 * @property-read \App\Models\School $school
 * @property-read \App\Models\AcademicYear $academicYear
 * @property-read \App\Models\StandardLink $standardLink
 * @property-read \App\Models\Subject $subject
 * @property-read \App\Models\User $createdBy
 * @property-read \App\Models\User $teacher
 * @property-read \App\Models\User $updatedBy
 * @property-read \App\Models\StudentHomework $studentHomework
 * @property-read \App\Models\HomeworkApproval $homeworkApproval
 * @property-read \Illuminate\Database\Eloquent\Collection $viewers
 * @mixin \Eloquent
 */
class Homework extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Common;

    protected $table='homeworks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
	    'school_id' , 'academic_year_id' , 'standardLink_id' , 'section_id' , 'subject_id' , 'description' , 'attachment' , 'date' , 'created_by' , 'updated_by','teacher_id','status'
	];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    //protected $dates = [ 'date' , 'deleted_at'];

    protected $casts = [
        'date' => 'datetime',
        'deleted_at' => 'datetime',
    ];

	/**
	 * Get the school associated with the homework.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function school()
    {
        return $this->belongsTo('\App\Models\School','school_id');
    }

    /**
     * Get the academic year associated with the homework.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function academicYear()
    {
        return $this->belongsTo('\App\Models\AcademicYear','academic_year_id');
    }

    /**
     * Get the standard link associated with the homework.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function standardLink()
    {
        return $this->belongsTo('\App\Models\StandardLink','standardLink_id');
    }

    /**
     * Get the subject associated with the homework.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subject()
    {
        return $this->belongsTo('\App\Models\Subject','subject_id');
    }

    /**
     * Get the user who created the homework.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo('\App\Models\User','created_by');
    }

    /**
     * Get the teacher associated with the homework.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo('\App\Models\User','teacher_id');
    }

    /**
     * Get the user who last updated the homework.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updatedBy()
    {
        return $this->belongsTo('\App\Models\User','updated_by');
    }

    /**
     * Get the attachment file path.
     *
     * @return string
     */
    public function getAttachmentPathAttribute()
    {
        return $this->getFilePath($this->attachment);
    }

    /**
     * Get the student homework associated with this homework.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function studentHomework()
    {
        return $this->hasOne('\App\Models\StudentHomework','homework_id','id');
    }

    /**
     * Get the homework approval associated with this homework.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function homeworkApproval()
    {
        return $this->hasOne('\App\Models\HomeworkApproval','homework_id','id');
    }

    /**
     * Get the student history records for this homework.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function viewers()
    {
        return $this->morphMany(StudentHistory::class, 'entity');
    }

    /**
     * Get the count of finished student homework submissions.
     *
     * @return int
     */
    public function getFinishedCountAttribute()
    {
        $students       = SiteHelper::getClassStudents($this->school_id,$this->academic_year_id,$this->standardLink_id);
        $students_id    = $students->pluck('id')->toArray();
        $finished       = \App\Models\StudentHomework::where('homework_id',$this->id)->whereIn('user_id',$students_id)->count();

        return $finished;
    }

    /**
     * Get the count of pending student homework submissions.
     *
     * @return int
     */
    public function getPendingCountAttribute()
    {
        $students       = SiteHelper::getClassStudents($this->school_id,$this->academic_year_id,$this->standardLink_id);
        $students_id    = $students->pluck('id')->toArray();
        $finished       = \App\Models\StudentHomework::where('homework_id',$this->id)->whereIn('user_id',$students_id)->count();
        $pending        = count($students_id) - $finished;

        return $pending;
    }

    /**
     * Get the attachment file type (image, audio, video, pdf, or empty).
     *
     * @return string
     */
    public function getAttachmentTypeAttribute()
    {
        if($this->attachment != null && $this->attachment !='')
        {
            $attachment = $this->getFilePath($this->attachment);
            $extension=pathinfo( $attachment, PATHINFO_EXTENSION);//dd($extension);
            if(in_array($extension,['jpg','jpeg','png']))
            {
              $type='image';
            }
            elseif(in_array($extension,['mp3']))
            {
                $type='audio';
            }
            elseif(in_array($extension,['mp4']))
            {
                $type='video';
            }
            elseif(in_array($extension,['pdf']))
            {
                $type='pdf';
            }
            else
            {
                 $type='';
            }
        }
        else
        {
            $type='';
        }

        return $type;
    }
}
