<?php

namespace App\Http\Resources\API\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbsentList extends JsonResource
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
            //
            'user_id' => $this->user_id,
            'user_name' => $this->user->FullName,
            'reason_id' => $this->reason_id,
            'reason' => $this->absentReason->title,
            'class' => $this->standardLink->StandardSection,
            'remarks' => $this->remarks,

        ];
    }
}
