<?php

namespace App\Http\Resources\API\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentAssignment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $file = [];
        foreach ($this->AssignmentFilePath as $id => $attachments) {
            foreach ($attachments as $key1 => $value) {
                if ($key1 == 'path') {
                    $file[$id] = $value;
                }
            }
        }

        $array = [
            //
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_name' => $this->student->FullName,
            'user_avatar' => $this->student->userprofile->AvatarPath,
            'roll_number' => $this->student->studentAcademicLatest->roll_number,
            'assignment_file' => $this->assignment_file == null ? '' : $this->AssignmentFilePath,
            'submitted_on' => $this->submitted_on == null ? '' : date('d M Y', strtotime($this->submitted_on)),
            'total_marks' => $this->assignment->marks == null ? null : $this->assignment->marks,
            'title' => $this->assignment->title == null ? null : $this->assignment->title,
            'description' => $this->assignment->description == null ? null : $this->assignment->description,
            'subject' => $this->assignment->subject->name == null ? null : $this->assignment->subject->name,
        ];

        if ($this->obtained_marks != null) {
            $array['obtained_marks'] = $this->obtained_marks;
        }
        if ($this->comments != null) {
            $array['comments'] = $this->comments;
        }
        if ($this->marks_given_on != null) {
            $array['marks_given_on'] = $this->marks_given_on == null ? '' : date('d M Y', strtotime($this->marks_given_on));
        }
        $array['status'] = $this->status;

        return $array;
    }
}
