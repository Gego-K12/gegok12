<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Teacherlink
 *
 * Model for managing teacher-subject-class assignments.
 *
 * @property int $id
 * @property int $school_id
 * @property int $academic_year_id
 * @property int $standardLink_id
 * @property int $subject_id
 * @property int $teacher_id
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read StandardLink $standardLink
 * @property-read Subject $subject
 * @property-read Userprofile $userprofile
 * @property-read User $teacher
 * @property-read Collection|LessonPlan[] $lessonPlan
 *
 * @mixin \Eloquent
 */
class Teacherlink extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'class_teacher_links';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'academic_year_id', 'standardLink_id', 'subject_id', 'teacher_id',
    ];

    protected $with = ['subject', 'teacher', 'standardLink'];

    /**
     * Get the standard link for this teacher assignment.
     *
     * @return BelongsTo
     */
    public function standardLink()
    {
        return $this->belongsTo('\App\Models\StandardLink', 'standardLink_id');
    }

    /**
     * Get the subject for this teacher assignment.
     *
     * @return BelongsTo
     */
    public function subject()
    {
        return $this->belongsTo('\App\Models\Subject', 'subject_id');
    }

    /**
     * Get the user profile for this teacher.
     *
     * @return BelongsTo
     */
    public function userprofile()
    {
        return $this->belongsTo('\App\Models\Userprofile', 'teacher_id', 'user_id');
    }

    /**
     * Get the teacher for this assignment.
     *
     * @return BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo('\App\Models\User', 'teacher_id')->where('usergroup_id', 5);
    }

    /**
     * Get the count of timetable entries for this teacher.
     *
     * @return int
     */
    public function getTeacherTimeTableAttribute()
    {
        return $this->belongsTo('\Gegok12\Timetable\Models\TempTimetable', 'teacher_id', 'teacher_id')->count();

    }

    /**
     * Get timetable entries for this teacher.
     *
     * @return HasMany
     */
    public function temp_timetable()
    {
        return $this->hasMany('\Gegok12\Timetable\Models\TempTimetable', 'teacher_id', 'teacher_id');
    }

    /**
     * Get lesson plans for this teacher assignment.
     *
     * @return HasMany
     */
    public function lessonPlan()
    {
        return $this->hasMany('\App\Models\LessonPlan', 'teacher_link_id', 'id');
    }//       public function getMondayAttribute()
    //     {

    //         $mentry=$this->hasMany('\App\Models\TempTimetable','teacher_id','teacher_id')->where('day','Monday')->orderBy('schedule')->get();
    //         $mcount=$this->hasMany('\App\Models\TempTimetable','teacher_id','teacher_id')->where('day','Monday')->count();

    //         if($mcount!=8)
    //         {
    //             $monday=['free','free','free','free','free','free','free','free'];
    //         for($i=0;$i<$mcount;$i++)
    //         {
    //            if($mentry[$i]->schedule==0)
    //            {
    //             $monday[$mentry[$i]->schedule]=$mentry[$i];
    //         }
    //          if($mentry[$i]->schedule==1)
    //            {
    //             $monday[$mentry[$i]->schedule]=$mentry[$i];
    //         }
    //          if($mentry[$i]->schedule==2)
    //            {
    //             $monday[$mentry[$i]->schedule]=$mentry[$i];
    //         }
    //          if($mentry[$i]->schedule==3)
    //            {
    //             $monday[$mentry[$i]->schedule]=$mentry[$i];
    //         }
    //          if($mentry[$i]->schedule==4)
    //            {
    //             $monday[$mentry[$i]->schedule]=$mentry[$i];
    //         }
    //          if($mentry[$i]->schedule==5)
    //            {
    //             $monday[$mentry[$i]->schedule]=$mentry[$i];
    //         }
    //          if($mentry[$i]->schedule==6)
    //            {
    //             $monday[$mentry[$i]->schedule]=$mentry[$i];
    //         }
    //         if($mentry[$i]->schedule==7)
    //            {
    //             $monday[$mentry[$i]->schedule]=$mentry[$i];
    //         }

    //         }
    // }
    // else
    // {
    //     $monday=$mentry;
    // }
    // return $monday;

    //     }
    //      public function getTuesdayAttribute()
    //     {

    //         $tuentry=$this->hasMany('\App\Models\TempTimetable','teacher_id','teacher_id')->where('day','Tuesday')->orderBy('schedule')->get();
    //         $tucount=$this->hasMany('\App\Models\TempTimetable','teacher_id','teacher_id')->where('day','Tuesday')->count();

    //         if($tucount!=8)
    //         {
    //             $tuesday=['free','free','free','free','free','free','free','free'];
    //         for($i=0;$i<$tucount;$i++)
    //         {
    //            if($tuentry[$i]->schedule==0)
    //            {
    //             $tuesday[$tuentry[$i]->schedule]=$tuentry[$i];
    //         }
    //          if($tuentry[$i]->schedule==1)
    //            {
    //             $tuesday[$tuentry[$i]->schedule]=$tuentry[$i];
    //         }
    //          if($tuentry[$i]->schedule==2)
    //            {
    //             $tuesday[$tuentry[$i]->schedule]=$tuentry[$i];
    //         }
    //          if($tuentry[$i]->schedule==3)
    //            {
    //             $tuesday[$tuentry[$i]->schedule]=$tuentry[$i];
    //         }
    //          if($tuentry[$i]->schedule==4)
    //            {
    //             $tuesday[$tuentry[$i]->schedule]=$tuentry[$i];
    //         }
    //          if($tuentry[$i]->schedule==5)
    //            {
    //             $tuesday[$tuentry[$i]->schedule]=$tuentry[$i];
    //         }
    //          if($tuentry[$i]->schedule==6)
    //            {
    //             $tuesday[$tuentry[$i]->schedule]=$tuentry[$i];
    //         }
    //         if($tuentry[$i]->schedule==7)
    //            {
    //             $tuesday[$tuentry[$i]->schedule]=$tuentry[$i];
    //         }

    //         }
    // }
    // else
    // {
    //     $tuesday=$tuentry;
    // }
    // return $tuesday;

    //     }

    //      public function getWednesdayAttribute()
    //     {

    //         $wentry=$this->hasMany('\App\Models\TempTimetable','teacher_id','teacher_id')->where('day','Wednesday')->orderBy('schedule')->get();
    //         $wcount=$this->hasMany('\App\Models\TempTimetable','teacher_id','teacher_id')->where('day','Wednesday')->count();

    //         if($wcount!=8)
    //         {
    //              $wednesday=['free','free','free','free','free','free','free','free'];
    //         for($i=0;$i<$wcount;$i++)
    //         {
    //            if($wentry[$i]->schedule==0)
    //            {
    //             $wednesday[$wentry[$i]->schedule]=$wentry[$i];
    //         }
    //          if($wentry[$i]->schedule==1)
    //            {
    //             $wednesday[$wentry[$i]->schedule]=$wentry[$i];
    //         }
    //          if($wentry[$i]->schedule==2)
    //            {
    //             $wednesday[$wentry[$i]->schedule]=$wentry[$i];
    //         }
    //          if($wentry[$i]->schedule==3)
    //            {
    //             $wednesday[$wentry[$i]->schedule]=$wentry[$i];
    //         }
    //          if($wentry[$i]->schedule==4)
    //            {
    //             $wednesday[$wentry[$i]->schedule]=$wentry[$i];
    //         }
    //          if($wentry[$i]->schedule==5)
    //            {
    //             $wednesday[$wentry[$i]->schedule]=$wentry[$i];
    //         }
    //          if($wentry[$i]->schedule==6)
    //            {
    //             $wednesday[$wentry[$i]->schedule]=$wentry[$i];
    //         }
    //         if($wentry[$i]->schedule==7)
    //            {
    //             $wednesday[$wentry[$i]->schedule]=$wentry[$i];
    //         }

    //         }
    // }
    // else
    // {
    //     $wednesday=$wentry;
    // }
    // return $wednesday;

    //     }
    //  public function getThursdayAttribute()
    //     {

    //         $thentry=$this->hasMany('\App\Models\TempTimetable','teacher_id','teacher_id')->where('day','Thursday')->orderBy('schedule')->get();
    //         $thcount=$this->hasMany('\App\Models\TempTimetable','teacher_id','teacher_id')->where('day','Thursday')->count();

    //         if($thcount!=8)
    //         {
    //              $thursday=['free','free','free','free','free','free','free','free'];
    //         for($i=0;$i<$thcount;$i++)
    //         {
    //            if($thentry[$i]->schedule==0)
    //            {
    //             $thursday[$thentry[$i]->schedule]=$thentry[$i];
    //         }
    //          if($thentry[$i]->schedule==1)
    //            {
    //             $thursday[$thentry[$i]->schedule]=$thentry[$i];
    //         }
    //          if($thentry[$i]->schedule==2)
    //            {
    //             $thursday[$thentry[$i]->schedule]=$thentry[$i];
    //         }
    //          if($thentry[$i]->schedule==3)
    //            {
    //             $thursday[$thentry[$i]->schedule]=$thentry[$i];
    //         }
    //          if($thentry[$i]->schedule==4)
    //            {
    //             $thursday[$thentry[$i]->schedule]=$thentry[$i];
    //         }
    //          if($thentry[$i]->schedule==5)
    //            {
    //             $thursday[$thentry[$i]->schedule]=$thentry[$i];
    //         }
    //          if($thentry[$i]->schedule==6)
    //            {
    //             $thursday[$thentry[$i]->schedule]=$thentry[$i];
    //         }
    //         if($thentry[$i]->schedule==7)
    //            {
    //             $thursday[$thentry[$i]->schedule]=$thentry[$i];
    //         }

    //         }
    // }
    // else
    // {
    //     $thursday=$thentry;
    // }
    // return $thursday;

    //     }
    //      public function getFridayAttribute()
    //     {

    //         $fentry=$this->hasMany('\App\Models\TempTimetable','teacher_id','teacher_id')->where('day','Friday')->orderBy('schedule')->get();
    //         $fcount=$this->hasMany('\App\Models\TempTimetable','teacher_id','teacher_id')->where('day','Friday')->count();

    //         if($fcount!=8)
    //         {
    //              $friday=['free','free','free','free','free','free','free','free'];

    //         for($i=0;$i<$fcount;$i++)
    //         {
    //            if($fentry[$i]->schedule==0)
    //            {
    //             $friday[$fentry[$i]->schedule]=$fentry[$i];
    //         }
    //          if($fentry[$i]->schedule==1)
    //            {
    //             $friday[$fentry[$i]->schedule]=$fentry[$i];
    //         }
    //          if($fentry[$i]->schedule==2)
    //            {
    //             $friday[$fentry[$i]->schedule]=$fentry[$i];
    //         }
    //          if($fentry[$i]->schedule==3)
    //            {
    //             $friday[$fentry[$i]->schedule]=$fentry[$i];
    //         }
    //          if($fentry[$i]->schedule==4)
    //            {
    //             $friday[$fentry[$i]->schedule]=$fentry[$i];
    //         }
    //          if($fentry[$i]->schedule==5)
    //            {
    //             $friday[$fentry[$i]->schedule]=$fentry[$i];
    //         }
    //          if($fentry[$i]->schedule==6)
    //            {
    //             $friday[$fentry[$i]->schedule]=$fentry[$i];
    //         }
    //         if($fentry[$i]->schedule==7)
    //            {
    //             $friday[$fentry[$i]->schedule]=$fentry[$i];
    //         }

    //         }
    // }
    // else
    // {
    //     $friday=$fentry;
    // }
    // return $friday;

    //     }
}
