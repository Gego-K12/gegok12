<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherDetail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $details = $this->getTeacherDetails();

        return
        [
            'id' => $this->id,
            'name' => $this->name,
            'mobile_no' => $this->mobile_no,
            'avatar' => $this->userprofile->AvatarPath,
            'fullname' => $this->FullName,
            'city' => $this->userprofile->city->name,
            'joining_date' => $this->userprofile->joining_date == '' ? null : date('Y-m-d', strtotime($this->userprofile->joining_date)),
            'age' => date('Y') - date('Y', strtotime(optional($this->userprofile)->date_of_birth)),
            'marital_status' => $this->userprofile->marital_status == '' ? null : ucwords($this->userprofile->marital_status),
            'details' => $details,
            'designation_name' => $details['designation_name'],
            'designation' => $details['designation'],
            'sub_designation' => $details['sub_designation'],
            'employee_id' => $details['employee_id'],
            'status' => $details['status'],
            'class_teacher' => $this->standardLink->StandardSection,
            'job_type' => $details['job_type'],
            'interested_in' => $details['interested_in'],
            'librarycard_number' => $this->librarycard->library_card_no,
        ];
    }
}
