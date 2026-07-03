<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [

            'schoolName' => ucwords($this->school->name),

            'fullName' => $this->FullName,

            'gender' => $this->userprofile->gender,

            'dateOfBirth' => date('d-m-Y', strtotime($this->userprofile->date_of_birth)),

            'bloodGroup' => strtoupper($this->userprofile->blood_group),

            'address' => $this->userprofile->address,

            'city' => $this->userprofile->city_id,

            'state' => $this->userprofile->state_id,

            'country' => $this->userprofile->country_id,

            'pincode' => $this->userprofile->pincode,

            'birthPlace' => $this->userprofile->birth_place,

            'nativePlace' => $this->userprofile->native_place,

            'motherTongue' => $this->userprofile->mother_tongue,

            'caste' => $this->userprofile->caste,

            'aadharNumber' => $this->aadhar_number,

            'EMISNumber' => $this->EMIS_number,

            'emailId' => $this->email,

            'mobileNo' => $this->mobile_no,

            'notes' => $this->userprofile->notes,

            'avatar' => $this->userprofile->AvatarPath,

            'class' => $this->studentAcademicLatest->standardLink->StandardSection,

            'standard_id' => $this->studentAcademicLatest->standardLink_id,

            'joiningDate' => $this->userprofile->joining_date == null ? null : date('d-m-Y', strtotime($this->userprofile->joining_date)),

            'registrationNumber' => $this->userprofile->registration_number,

            'academicYear' => $this->studentAcademicLatest->academicYear->name,

            'rollNumber' => $this->studentAcademicLatest->roll_number,

            'idCardNumber' => $this->studentAcademicLatest->id_card_number,

            'boardRegistrationNumber' => $this->studentAcademicLatest->board_registration_number,

            'modeOfTransport' => $this->studentAcademicLatest->mode_of_transport,

            'transportDetails' => $this->studentAcademicLatest->transport_details,

            'siblings' => $this->studentAcademicLatest->siblings,

            'siblingRelation' => $this->studentAcademicLatest->sibling_details,
        ];
    }
}
