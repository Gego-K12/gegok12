<?php

namespace App\Http\Resources\API\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DisciplineResource extends JsonResource
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
           'incidentDate' => date('d M Y', strtotime($this->incident_date)),
           'teacher' => $this->teacher->FullName,
           'incidentDetail' => $this->incident_detail,
           'class_name' => $this->standardLink_id == null ? '' : $this->standardLink->StandardSection,
           'class_id' => $this->standardLink_id == null ? '' : $this->standardLink_id,
           'student_id' => $this->user_id,
           'student_name' => $this->user->FullName,
           'response' => $this->response == null ? '' : $this->response,
           'action_taken' => $this->action_taken,
           'attachments' => $this->attachments == null ? '' : $this->AttachmentPath,
           'notifyParents' => $this->notify_parents,
           'is_seen' => $this->is_seen,
           'type' => $this->type == null ? '' : $this->type,
           'media_type' => $this->media_type == null ? '' : $this->media_type,
        ];
    }
}
