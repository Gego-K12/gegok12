<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $parent_id = [];
        foreach ($this->parents as $parent) {
            if ($parent->userParent) {
                $parent_id[] = $parent->userParent->id;
            }
        }

        $designation = optional($this->teacherprofile->first())->designation;
        $pro_design = $designation != null ? ucwords(str_replace('_', ' ', $designation)) : '';

        return
        [
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->email,
            'mobile_no' => $this->mobile_no,
            'avatar' => optional($this->userprofile)->AvatarPath,
            'firstname' => optional($this->userprofile)->firstname.' '.optional($this->userprofile)->lastname,
            'lastname' => optional($this->userprofile)->lastname,
            'fullname' => $this->FullName,
            'class' => optional(optional($this->studentAcademicLatest)->standardLink)->StandardSection,
            'parent_id' => $parent_id,
            'designation' => $designation,
            'designation_display' => $pro_design,
            'date_of_birth' => optional($this->userprofile)->date_of_birth == null ? null : date('d M Y', strtotime($this->userprofile->date_of_birth)),
            'status' => $this->status,
            'librarycard_number' => optional($this->librarycard)->library_card_no,
            'book_limit' => optional($this->librarycard)->book_limit,
        ];
    }
}
