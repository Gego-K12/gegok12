<?php
// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors
/**
 * Class Admission
 *
 * Model for student admissions.
 *
 * @property int $id
 * @property int $school_id
 * @property int $academic_year_id
 * @property int $standard_id
 * @property string $name
 * ... (other fillable fields)
 * @property \Carbon\Carbon|null $deleted_at
 *
 * @mixin \Eloquent
 */
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'school_id' , 'academic_year_id' , 'standard_id' , 'name' , 'date_of_birth' , 'gender' , 'height' , 'weight' , 'birth_place' , 'nationality' , 'avatar' , 'religion' , 'community' , 'mother_tongue' , 'identification_marks' , 'aadhar_number' , 'blood_group' , 'school_last_studied' , 'reason_for_leaving' , 'permanent_address' , 'address_for_communication' , 'siblings' , 'half_yearly_mark_details' , 'board_of_education' , 'choice_of_language' , 'group_selection' , 'board_registration_number' , 'father_name' , 'father_qualification_id' , 'father_designation' , 'father_occupation' , 'father_organisation' , 'father_income' , 'father_mobile_no' , 'father_email' , 'father_aadhar_number' , 'father_avatar' , 'mother_name' , 'mother_qualification_id' , 'mother_designation' , 'mother_occupation' , 'mother_organisation' , 'mother_income' , 'mother_mobile_no' , 'mother_email' , 'mother_aadhar_number' , 'mother_avatar' , 'emergency_contact_1' , 'relation_with_student_1' , 'emergency_contact_2' , 'relation_with_student_2' , 'medical_history' , 'medical_details' , 'extra_curricular_activities' , 'activities' , 'mode_of_transport' , 'transport_details' , 'application_no' , 'application_status' , 'section_id' , 'payment_status' , 'fee_group_id' , 'remarks'
    ];
    
    protected $casts = [
        'half_yearly_mark_details' => 'array',
        'transport_details' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the school that owns the admission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School','school_id');
    }

    /**
     * Get the academic year for the admission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function academicYear()
    {
        return $this->belongsTo('App\Models\AcademicYear','academic_year_id');
    }

    /**
     * Get the standard for the admission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function standard()
    {
        return $this->belongsTo('App\Models\Standard','standard_id');
    }
}
