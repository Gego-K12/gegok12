<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Assignment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->attachment != null)
        {
            $attachment = $this->AttachmentPath;
        }
        else
        {
            $attachment = '';
        }

        if ($this->studentAssignment->status == 'submitted') 
        {
            $studentAssignmentStatus = 1;
        }
        elseif ($this->studentAssignment->status == 'completed')
        {
            $studentAssignmentStatus = 1;
        }
        else
        {
            $studentAssignmentStatus = 0;
        }

        $start_date = date('Y-m-d',strtotime($this->assigned_date));
        $today = date('Y-m-d');
        if($today >= $start_date)
        {
            $show = 1;
        }
        else
        {
            $show = 0;
        }
        
        return 
        [
            //
            'id'                        =>  $this->id,
            'class'                     =>  $this->standardLink->StandardSection,
            'title'                     =>  $this->title,
            'subject'                   =>  $this->subject->name,
            'description'               =>  $this->description,
            'assigned_date'             =>  date('d M Y', strtotime($this->assigned_date)),
            'submission_date'           =>  date('d M Y', strtotime($this->submission_date)),
            'attachment'                =>  $attachment,
            'status_display'            =>  ucfirst($this->assignmentApproval->status),
            'status'                    =>  $this->status,
            'comments'                  =>  $this->assignmentApproval->comments,
            'studentAssignmentStatus'   =>  $studentAssignmentStatus,
            'studentStatus'             =>  (($this->studentAssignment->status)?ucwords($this->studentAssignment->status):''),
            'assignment_file'           =>  $this->studentAssignment->AttachmentPath,
            'marks'                     =>  $this->marks,
            'student_assignment_id'     =>  $this->studentAssignment->id,
            'show'                      =>  $show,
            'approve_status'            =>  $this->assignmentApproval->status,
        ];
    }
}