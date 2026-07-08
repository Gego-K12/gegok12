<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Grade extends JsonResource
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
            'name' => $this->name,
            'grades' => $this->grades,
            'min_mark' => $this->min_mark,
            'max_mark' => $this->max_mark,
            'status' => $this->status,

        ];
    }
}
