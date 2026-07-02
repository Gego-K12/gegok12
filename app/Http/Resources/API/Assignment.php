<?php

namespace App\Http\Resources\API;

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
            $extension=pathinfo( $attachment, PATHINFO_EXTENSION);
            if(in_array($extension,['jpg','jpeg','png']))
            {
              $type='image';
            }
            elseif(in_array($extension,['mp3']))
            {
                $type='audio';
            }
            elseif(in_array($extension,['mp4']))
            {
                $type='video';
            }
            elseif(in_array($extension,['pdf']))
            {
                $type='pdf';
            }
            else
            {
                 $type='';
            }
        }
        else
        {
            $attachment = '';
            $type='';
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
        
        return 
        [
            //
            'id'                        =>  $this->id,
            'class'                     =>  $this->standardLink->StandardSection,
            'title'                     =>  $this->title,
            'description'               =>  $this->description,
            'assignedDate'              =>  $this->assigned_date=='' ? null:date('d-m-Y', strtotime($this->assigned_date)),
            'submissionDate'            =>  $this->submission_date=='' ? null:date('d-m-Y', strtotime($this->submission_date)),
            'attachment'                =>  $attachment,
            'status'                    =>  ucwords($this->status),
            'studentAssignmentStatus'   =>  $studentAssignmentStatus,
            'assignmentFile'            =>  $this->studentAssignment->AssignmentFilePath,
            'marks'                     =>  $this->marks,
            'studentAssignmentId'       =>  $this->studentAssignment->id,
            'type'                      =>  $type,
        ];
    }
}
