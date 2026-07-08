<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiblingListResource extends JsonResource
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
            'fullname' => $this->userStudent->FullName,
            'relation' => $this->userStudent->userprofile->gender == 'male' ? 'Brother' : 'Sister',
            'date_of_birth' => date('d-m-Y', strtotime($this->userStudent->userprofile->date_of_birth)),
            'standard_section' => $this->userStudent->studentAcademicLatest->standardLink->StandardSection,
        ];
    }
}
