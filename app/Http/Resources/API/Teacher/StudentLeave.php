<?php

namespace App\Http\Resources\API\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentLeave extends JsonResource
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
            'name' => $this->teacher->FullName,
            'from' => date('d M Y H:i:s', strtotime($this->from_date)),
            'to' => date('d M Y H:i:s', strtotime($this->to_date)),
            'reason' => $this->absentReason->title,
            'remarks' => $this->remarks,
            'approved_by' => $this->approvedUser->FullName,
            'approved_on' => $this->approved_on == null ? null : date('d M Y', strtotime($this->approved_on)),
            'comments' => $this->comments,
            'status' => $this->status,
            'status_name' => ucfirst($this->status),
            'standard_name' => $this->standardLink->StandardSection,
        ];
    }
}
