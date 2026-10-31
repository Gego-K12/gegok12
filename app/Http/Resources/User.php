<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{

   /**
    * Transform the resource into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function toArray($request)
    {
        $parent_id = $this->parents->pluck('parent_id')->all();
        $designation = config('academy_backend.enabled') && (int) $this->usergroup_id === 6
            ? null : $this->teacherprofile->first()?->designation;
        $pro_design = $designation ? ucwords(str_replace('_', ' ', $designation)) : '';
        $library = config('academy_backend.enabled') ? null : $this->librarycard;
        return 
        [
            'id'                    =>  $this->id,
            'name'                  =>  $this->name,
            'label'                 =>  $this->email,
            'mobile_no'             =>  $this->mobile_no,
            'avatar'                =>  optional($this->userprofile)->AvatarPath,
            'firstname'             =>  trim(($this->userprofile?->firstname ?? '').' '.($this->userprofile?->lastname ?? '')),
            'lastname'              =>  $this->userprofile?->lastname,
            'fullname'              =>  $this->FullName,
            'class'                 =>  $this->studentAcademicLatest?->standardLink?->StandardSection,
            'parent_id'             =>  $parent_id == null ? []:$parent_id,
            'designation'           =>  $designation,
            'designation_display'   => $pro_design,
            'date_of_birth'         =>  $this->userprofile?->date_of_birth == null ? null:date('d M Y',strtotime($this->userprofile->date_of_birth)),
            'status'                =>  $this->status,
            'librarycard_number'        => $library?->library_card_no,
            'book_limit' => $library?->book_limit,
        ];
    }
}
