<?php

namespace App\Http\Resources\API\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class Homework extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->standardLink_id != null)
        {
            $class_name = $this->standardLink->StandardSection;
        }
        else
        {
            $class_name = '--';
        }

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
        $role = 'teacher';
        if(Auth::user()->hasRole('principal'))
        {
            $role = 'principal';
        }


        return 
        [
            'id'                =>  $this->id,
            'class_name'        =>  $class_name,
            'subject_name'      =>  $this->subject->name,
            'date'              => $this->date ? date('d-m-Y', strtotime($this->date)) : '',
            'description'       =>  $this->description,
            'attachment'        =>  $attachment,
            'pending_count'     =>  $this->PendingCount,
            'status_display'    =>  ucwords($this->status),//$this->homeworkApproval->status
            'status'            =>  $this->status,//$this->homeworkApproval->status
            'comments'          =>  $this->homeworkApproval->comments,
            'type'              =>  $type,
            'submission_date'   => $this->submission_date ? date('d-m-Y', strtotime($this->submission_date)) : '',
            'approve_status'            =>  $this->homeworkApproval->status,
            'role'                      =>  $role 
        ];
    }
}