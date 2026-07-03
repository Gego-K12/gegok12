<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffAttendanceResource extends JsonResource
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
           'user_id' => $this->user_id,
           'user_name' => $this->user->name,
           'user_fullname' => $this->user->FullName,
           'date' => date('d M Y', strtotime($this->date)),
           'id_date' => date('d_m_y', strtotime($this->date)).'_'.$this->session,
           'session' => ucfirst($this->session),
           'reason' => $this->absentReason->title,
           'remarks' => $this->remarks,
           'recorded_by' => $this->admin->FullName == null ? ucfirst($this->admin->name) : ucfirst($this->admin->FullName),
           'created_at' => date('d M Y', strtotime($this->created_at)),
        ];
    }
}
