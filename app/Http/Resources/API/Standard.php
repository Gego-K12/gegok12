<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Standard extends JsonResource
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
            'academic_year_id' => $this->academic_year_id,
            'standard_name' => $this->standard->name,
            'section_name' => $this->section->name,
            'standard_id' => $this->standard->id,
            'section_id' => $this->section_id,
            'no_of_students' => $this->no_of_students,
            'status' => $this->status,

        ];
    }
}
