<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Discipline extends JsonResource
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
            'incident_date' => date('d-m-Y H:i:s', strtotime($this->incident_date)),
            'incident_detail' => str_limit($this->incident_detail, 10, '...'),
            'teacher_username' => $this->teacher->name,
            'teacher_name' => ucwords($this->teacher->FullName),
            'attachment' => $this->attachments == null ? null : $this->AttachmentPath,
        ];
    }
}
