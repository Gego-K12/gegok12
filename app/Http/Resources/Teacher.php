<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Teacher extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (config('academy_backend.enabled')) {
            $profile = $this->teacherprofile->first();
            $details = ['designation' => $profile?->designation,
                'designation_name' => $profile?->designation ? str_replace('_', ' ', ucwords($profile->designation)) : '',
                'sub_designation' => $profile?->sub_designation];
        } else {
            $details = $this->getTeacherDetails();
        }
        $library = config('academy_backend.enabled') ? null : $this->librarycard;
        return 
        [
            //
            'id'                =>  $this->id,
            'name'              =>  $this->name,
            'email'             =>  $this->email,
            'mobile_no'         =>  $this->mobile_no,
            'avatar'            =>  $this->userprofile->AvatarPath,
            'fullname'          =>  $this->FullName,
            'designation'       =>  $details['designation'],
            'designation_name'  =>  $details['designation_name'],
            'sub_designation'   =>  $details['sub_designation'],
            'date_of_birth'     =>  date('d M Y',strtotime($this->userprofile->date_of_birth)),
            'status'            =>  $this->status,
            'librarycard_number'        => $library?->library_card_no,
            'book_limit' => $library?->book_limit,
        ];
    }
}
