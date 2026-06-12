<?php

namespace App\Http\Resources\API\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            $extension=pathinfo( $attachment, PATHINFO_EXTENSION);//dd($extension);
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

        $role = 'teacher';
        if(Auth::user()->hasRole('principal'))
        {
            $role = 'principal';
        }
        
        return 
        [
            //
            'id'                        =>  $this->id,
            'class'                     =>  $this->standardLink->StandardSection,
            'title'                     =>  $this->title,
            'subject'                   =>  $this->subject->name,
            'description'               =>  $this->description,
            'assigned_date' => $this->assigned_date ? date('d M Y', strtotime($this->assigned_date)) : null,

            'submission_date' => $this->submission_date ? date('d M Y', strtotime($this->submission_date)) : null,
            'attachment'                =>  $attachment,
            'status_display'            =>  ucwords($this->status), //$this->assignmentApproval->status
            'status'                    =>  $this->status, //$this->assignmentApproval->status
            'comments'                  =>  $this->assignmentApproval->comments,
            //'studentAssignmentStatus'   =>  $studentAssignmentStatus,
            //'studentStatus'             =>  ucwords($this->studentAssignment->status),
            //'assignment_file'           =>  $this->studentAssignment->AttachmentPath,
            'marks'                     =>  $this->marks,
            //'student_assignment_id'     =>  $this->studentAssignment->id,
            'show'                      =>  $show,
            'type'                      =>  $type,
            'approve_status'            =>  $this->assignmentApproval->status,
            'role'                      =>  $role,
            'created_by'                => $this->teacher->fullname,  
        ];
    }
}