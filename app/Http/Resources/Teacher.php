<?php

namespace App\Http\Resources;

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
        $profile = $this->latestTeacherProfile;

        return
        [
            //
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile_no' => $this->mobile_no,
            'avatar' => $this->userprofile->AvatarPath,
            'title' => $this->userprofile->gender == 'female' ? 'Ms.' : 'Mr.',
            'fullname' => $this->FullName,
            'employee_id' => optional($profile)->employee_id,
            'designation' => optional($profile)->designation,
            'designation_name' => optional($profile)->designation_name,
            'sub_designation' => optional($profile)->sub_designation,
            'date_of_birth' => date('d M Y', strtotime($this->userprofile->date_of_birth)),
            'joining_date' => $this->userprofile->joining_date != null ? date('d M Y', strtotime($this->userprofile->joining_date)) : null,
            'relieved_at' => $this->userprofile->relieved_at != null ? date('d M Y', strtotime($this->userprofile->relieved_at)) : null,
            'status' => $this->status,
            'last_login_at' => optional($this->lastLogin)->created_at ? $this->lastLogin->created_at->format('d M Y, h:i A') : null,
            'librarycard_number' => optional($this->librarycard)->library_card_no,
            'book_limit' => optional($this->librarycard)->book_limit,
            'class_teacher_of' => $this->standardLink->standard_section ?? null,
            'subject_teacher_of' => $this->teacherlink->filter(function ($link) {
                return $link->standardLink != null && $link->subject != null;
            })->map(function ($link) {
                return $link->standardLink->standard_section.' - '.$link->subject->name;
            })->values(),
        ];
    }
}
