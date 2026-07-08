<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceUser extends JsonResource
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
            'date' => date('d M Y', strtotime($this->date)),
            'session' => ucfirst($this->session),
            'reason' => $this->reason_id == null ? '--' : $this->absentReason->title,
            'remarks' => $this->remarks,
            'recorded_by_name' => $this->recordedby->name, // $this->admin->name,
            'recorded_by' => $this->recordedby->FullName, // $this->admin->FullName,
            'recorded_on' => date('d M Y', strtotime($this->created_at)),
        ];
    }
}
