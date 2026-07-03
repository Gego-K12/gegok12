<?php

namespace App\Http\Resources\Student;

use App\Models\StudentHomework;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class Homework extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $student_homework = StudentHomework::where([['homework_id', $this->id], ['user_id', Auth::id()]])->first();
        if ($this->submission_date != null) {
            if (date('Y-m-d') <= date('Y-m-d', strtotime($this->date))) {
                $student_homework_status = 0;
            }
        }

        if ($student_homework != null) {
            if ($student_homework->status == 'unchecked') {
                $student_homework_status = 0;
            } else {
                $student_homework_status = 1;
            }

            $attachment_file = $student_homework->AttachmentPath;
        }

        if ($this->standardLink_id != null) {
            $class_name = $this->standardLink->StandardSection;
        } else {
            $class_name = '--';
        }

        if ($this->attachment != null) {
            $attachment = $this->AttachmentPath;
        } else {
            $attachment = '';
        }

        return
        [
            'id' => $this->id,
            'class_name' => $class_name,
            'subject_name' => $this->subject->name,
            'date' => date('d-m-Y', strtotime($this->date)),
            'description' => $this->description,
            'attachment' => $attachment,
            'studentHomeworkStatus' => $student_homework_status,
            'student_homework_id' => $student_homework->id,
            'checked_by' => $student_homework->checked_by == null ? '--' : $student_homework->teacher->FullName,
            'checked_on' => $student_homework->checked_on == null ? '--' : date('d-m-Y', strtotime($student_homework->checked_on)),
            'comments' => $student_homework->comments,
            'reply_comment' => $student_homework->reply_comment,
            'submission_date' => $this->submission_date == null ? '' : date('d-m-Y', strtotime($this->submission_date)),
        ];
    }
}
