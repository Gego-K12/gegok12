<?php

namespace App\Http\Resources\API\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherLink extends JsonResource
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
            //
            'standardLink_id' => $this->standardLink_id,
            'subject_id' => $this->subject_id,
            'subject_name' => $this->subject->name,
        ];
    }
}
