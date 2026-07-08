<?php

namespace App\Http\Resources;

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
            'teacher_avatar' => $this->teacher->userprofile->AvatarPath,
            'teacher_name' => $this->teacher->name,
            'teacher_fullname' => $this->teacher->FullName,
            'subject_name' => $this->subject->name,
        ];
    }
}
