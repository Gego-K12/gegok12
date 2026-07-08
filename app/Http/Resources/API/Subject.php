<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Subject extends JsonResource
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
            'standard_id' => $this->standardLink_id,
            /* 'standard_id' => $this->teacherlink[0]['standardLink_id'],
             'name' => $this->name,
             'code' => $this->code,
             'type' => $this->type,
             'status' =>$this->status,*/
            // 'standard_id' => $this->teacherlink->standardLink->id,
            'subject_id' => $this->id,
            'subject_name' => $this->name,

        ];
    }
}
