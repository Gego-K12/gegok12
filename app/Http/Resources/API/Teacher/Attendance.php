<?php

namespace App\Http\Resources\API\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Attendance extends JsonResource
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
            'id' => $this->id,
            'date' => date('d M Y', strtotime($this->date)),
            'session' => ucfirst($this->session),
            'reason' => $this->absentReason->title,
            'remarks' => $this->remarks,
        ];
    }
}
