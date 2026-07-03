<?php

namespace App\Http\Resources\API\Teacher;

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
        return
        [
            //
            'subject_id' => $this->subject->id,
            'subject_name' => $this->subject->name,
        ];
    }
}
