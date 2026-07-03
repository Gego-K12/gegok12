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
        $i = 0;
        foreach ($this->parents as $parent) {
            $parent_id[$i] = $parent->userParent->id;
            $i++;
        }
        $pro_design = '';
        if ($this->teacherprofile[0]['designation'] != null) {

            $pro_design = ucwords(str_replace('_', ' ', $this->teacherprofile[0]['designation']));
        }

        return
        [
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->email,
            'mobile_no' => $this->mobile_no,
            'avatar' => optional($this->userprofile)->AvatarPath,
            'firstname' => $this->userprofile->firstname.' '.$this->userprofile->lastname,
            'lastname' => $this->userprofile->lastname,
            'fullname' => $this->FullName,
            'class' => $this->studentAcademicLatest->standardLink->StandardSection,
            'parent_id' => $parent_id == null ? [] : $parent_id,
            'designation' => $this->teacherprofile[0]['designation'],
            'designation_display' => $pro_design,
            'date_of_birth' => $this->userprofile->date_of_birth == null ? null : date('d M Y', strtotime($this->userprofile->date_of_birth)),
            'status' => $this->status,
            'librarycard_number' => $this->librarycard->library_card_no,
            'book_limit' => $this->librarycard->book_limit,
        ];
    }
}
