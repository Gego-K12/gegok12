<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Student extends JsonResource
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

            'id' => $this->id,
            'school_id' => $this->school_id,
            'standard_id' => $this->markStandard->standard_id,
            'user_id' => $this->markUser->user_id,
            'academic_year_id' => $this->markacademic->academic_year_id,

        ];
    }
}
