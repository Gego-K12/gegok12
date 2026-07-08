<?php

namespace App\Http\Resources\Student;

use App\Models\StudentAssignment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class Assignment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->attachment != null) {
            $attachment = $this->AttachmentPath;
        } else {
            $attachment = '';
        }

        $array = [
            //
            'id' => $this->id,
            'title' => $this->title,
            'subject' => $this->subject->name,
            'description' => $this->description,
            'assigned_date' => date('d M Y', strtotime($this->assigned_date)),
            'submission_date' => date('d M Y', strtotime($this->submission_date)),
            'attachment' => $attachment,
            'marks' => $this->marks,
        ];
        $studentAssignment = StudentAssignment::where([['assignment_id', $this->id], ['user_id', Auth::id()]])->first();

        if ($studentAssignment != null) {
            if ($studentAssignment->status == 'submitted') {
                $studentAssignmentStatus = 1;
            } elseif ($studentAssignment->status == 'completed') {
                $studentAssignmentStatus = 1;
            } else {
                $studentAssignmentStatus = 0;
            }
            $array['studentAssignmentStatus'] = $studentAssignmentStatus;
            $array['studentStatus_display'] = ucwords($studentAssignment->status);
            $array['studentStatus'] = $studentAssignment->status;
            $array['assignment_file'] = $studentAssignment->AssignmentFilePath;
            $array['student_assignment_id'] = $studentAssignment->id;
        }

        return $array;
    }
}
