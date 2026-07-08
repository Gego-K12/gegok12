<?php

namespace App\Http\Resources\API;

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
        return
        [
            'id' => $this->id,
            'schoolId' => $this->school_id,
            'subjectId' => $this->subject->id,
            'standardId' => $this->standardLink->id,
            'name' => $this->teacher->FullName,
        ];
    }
}
