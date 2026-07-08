<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoRoomUser extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return
        [
            'id' => $this->id,
            'fullname' => $this->FullName,
            'email' => $this->email,
            'mobile_no' => $this->mobile_no,
            'class' => $this->studentAcademicLatest->standardLink->StandardSection,
            'roll_number' => $this->studentAcademicLatest->roll_number,
        ];
    }
}
