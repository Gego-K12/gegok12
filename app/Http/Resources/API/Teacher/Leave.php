<?php

namespace App\Http\Resources\API\Teacher;

use carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Leave extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $start = Carbon::parse(date('Y-m-d', strtotime($this->from_date)));
        $end = Carbon::parse(date('Y-m-d', strtotime($this->to_date)));
        $leave_days = $start->diffInDays($end) + 1;
        if ($this->session == 'afternoon' || $this->session == 'forenoon') {
            $leave_days = $leave_days / 2;
        }

        return
        [
            //
            'id' => $this->id,
            'teacher_name' => $this->teacher->FullName,
            'from_date' => date('d M Y', strtotime($this->from_date)),
            'to_date' => date('d M Y', strtotime($this->to_date)),
            'reason' => $this->absentReason->title,
            'remarks' => $this->remarks,
            'leave_type' => $this->leaveType->name,
            'approved_by' => $this->approvedUser->FullName,
            'approved_on' => $this->approved_on == null ? null : date('d M Y', strtotime($this->approved_on)),
            'comments' => $this->comments,
            'status' => ucfirst($this->status),
            'applied_at' => date('d M Y h:i:s A', strtotime($this->created_at)),
            'leave_days' => "$leave_days",
            'session' => $this->session == null ? '' : ucfirst($this->session),
        ];
    }
}
