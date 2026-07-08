<?php

namespace App\Http\Resources;

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

        if ($this->status == 'completed') {
            $status = 1;
        } else {
            $status = 0;
        }

        return
        [
            //
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_name' => $this->student->FullName,
            'roll_number' => $this->student->studentAcademicLatest->roll_number,
            'assignment_file' => $this->assignment_file == null ? '' : $this->AssignmentFilePath,
            'total_marks' => $this->assignment->marks == null ? '' : $this->assignment->marks,
            'obtained_marks' => $this->obtained_marks == null ? '' : $this->obtained_marks,
            'submitted_on' => $this->submitted_on == null ? '' : date('d M Y', strtotime($this->submitted_on)),
            'comments' => $this->comments == null ? '' : $this->comments,
            'marks_given_on' => $this->marks_given_on == null ? '' : date('d M Y', strtotime($this->marks_given_on)),
            'status' => $status,
        ];
    }
}
