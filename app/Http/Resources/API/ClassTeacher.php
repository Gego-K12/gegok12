<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassTeacher extends JsonResource
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
            'teacherAvatar' => $this->teacher->userprofile->AvatarPath,
            'teacherFullname' => $this->teacher->FullName,
            'teacherId' => $this->teacher->id,
            'subjectName' => $this->subject->name,
            'subjectId' => $this->subject->id,
        ];
    }
}
