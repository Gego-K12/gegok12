<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CallLog extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->call_type == 'incoming') {
            $call_detail = $this->incoming_number;
        } else {
            $call_detail = $this->outgoing_number;
        }

        return [

            'id' => $this->id,
            'school_id' => $this->school_id,
            'academic_year_id' => $this->academic_year_id,
            'calling_purpose' => $this->calling_purpose,
            'call_type' => $this->call_type,
            'name' => $this->name,
            'incoming_number' => $this->incoming_number,
            'outgoing_number' => $this->outgoing_number,

            'call_detail' => $call_detail,
            'call_date' => $this->call_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'duration' => $this->duration,
            'description' => $this->description,
            'entry_by' => $this->entry_by,

        ];
    }
}
