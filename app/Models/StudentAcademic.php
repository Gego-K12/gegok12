<?php
// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Tags\HasTags;

/**
 * Class StudentAcademic
 *
 * Model for managing student academic information and records.
 *
 * @property int $id
 * @property int $school_id
 * @property int $academic_year_id
 * @property int $user_id
 * @property int $standardLink_id
 * @property string $roll_number
 * @property string $id_card_number
 * @property string $board_registration_number
 * @property string $mode_of_transport
 * @property array $transport_details
 * @property string $siblings
 * @property int $siblings_count
 * @property array $sibling_details
 * @property string $height
 * @property string $weight
 * @property string $medication_problems
 * @property string $medication_needs
 * @property string $medication_allergies
 * @property string $food_allergies
 * @property string $other_allergies
 * @property string $other_medical_information
 * @property string $academic_status
 * @property string $bus_pass
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read \App\Models\School $school
 * @property-read \App\Models\AcademicYear $academicYear
 * @property-read \App\Models\User $user
 * @property-read \App\Models\StandardLink $standardLink
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mark[] $markUser
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mark[] $markStandard
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mark[] $markAcademic
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Timetable[] $timetable
 * @mixin \Eloquent
 */
class StudentAcademic extends Model
{
    //
    use SoftDeletes;
    use HasFactory;
     use HasTags;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'student_academics';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id' , 'academic_year_id' , 'user_id' , 'standardLink_id' , 'roll_number' , 'id_card_number' , 'board_registration_number' , 'mode_of_transport' , 'transport_details' , 'siblings' , 'siblings_count' , 'sibling_details' , 'height' , 'weight' , 'medication_problems' , 'medication_needs' , 'medication_allergies' , 'food_allergies' , 'other_allergies' , 'other_medical_information' , 'academic_status','bus_pass'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'transport_details' => 'array' , 'sibling_details' => 'array',
    ];

    /**
     * Get the school for this student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
    	return $this->belongsTo('\App\Models\School','school_id');
    }

    /**
     * Get the academic year for this record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function academicYear()
    {
        return $this->belongsTo('\App\Models\AcademicYear','academic_year_id');
    }

    /**
     * Get the student user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo('\App\Models\User','user_id');
    }

    /**
     * Get the standard/grade link for this student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function standardLink()
    {
    	return $this->belongsTo('\App\Models\StandardLink','standardLink_id');
    }

    /**
     * Get marks for this student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function markUser()
    {
        return $this->hasMany('\App\Models\Mark','user_id','id');
    }

    /**
     * Get marks for this standard.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function markStandard()
    {
        return $this->hasMany('\App\Models\Mark','standard_id','id');
    }

    /**
     * Get marks for this academic year.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function markAcademic()
    {
        return $this->hasMany('\App\Models\Mark','academic_year_id','id');
    }

    /**
     * Get timetable for this academic year.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timetable()
    {
        return $this->hasMany('\App\Models\Timetable','academic_year_id','id');
    }
}
