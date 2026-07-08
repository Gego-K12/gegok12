<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceStudentList extends JsonResource
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
            'standardLink_id' => $this->standardLink_id,
            'user_id' => $this->user->id,
            'id' => $this->id,
            'name' => $this->user->FullName,
            'avatar' => $this->user->userprofile->AvatarPath,
        ];
    }
}
