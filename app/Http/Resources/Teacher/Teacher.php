<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Teacher extends JsonResource
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
            //
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile_no' => $this->mobile_no,
            'avatar' => $this->userprofile->AvatarPath,
            'fullname' => $this->FullName,
            'designation' => $details['designation'],
            'designation_name' => $details['designation_name'],
            'sub_designation' => $details['sub_designation'],
            'date_of_birth' => date('d M Y', strtotime($this->userprofile->date_of_birth)),
        ];
    }
}
