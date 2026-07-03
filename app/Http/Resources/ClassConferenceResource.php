<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassConferenceResource extends JsonResource
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
            'title' => $this->name,
            'description' => $this->description,
            'slug' => $this->slug,
            'roomId' => $this->room_id,
            'composeId' => $this->compose_id,
            'status' => $this->status,
            'compose_status' => $this->compose_status,
            'created_by' => $this->userInfo->FullName,
            'avatar' => optional($this->userInfo->userprofile)->AvatarPath,
            'standard_name' => $this->standard == null ? '' : $this->standardlink->StandardSection,
            'subject' => $this->subject_id == null ? '' : $this->subject->name,
            'joining_date' => $this->joining_date == null ? '' : date('d-m-Y', strtotime($this->joining_date)),
            'start_time' => $this->joining_date == null ? '' : date('h:i A', strtotime($this->joining_date)),
            'duration' => $this->duration == null ? '' : "$this->duration",
            'end_time' => $this->joining_date == null ? '' : date('h:i A', strtotime('+'.$duration.'minute', strtotime($this->joining_date))),
            'video_path' => $this->compose_id == null ? '' : asset('uploads/live-stream/'.$this->compose_id.'.mp4'),
        ];
    }
}
