<?php

namespace App\Http\Resources\API\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonPlan extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $hour = date('H',strtotime($this->duration));
        $minutes = date('i',strtotime($this->duration));
        if($hour == '00')
        {
            $duration = $minutes.' minutes';
        }
        elseif($minutes == '00')
        {
            $duration = $hour.' hours';
        }
        else
        {
            $duration = $hour.' hours '.$minutes.' minutes';
        }
        return 
        [
            //
            'id'                =>  $this->id,
            'class'             =>  $this->teacherlink->standardLink->StandardSection,
            'subject'           =>  $this->teacherlink->subject->name,
            'teacherFullname'   =>  $this->teacherlink->teacher->FullName,
            'unitNo'            =>  $this->unit_no,
            'unitName'          =>  $this->unit_name,
            'title'             =>  $this->title,
            'duration'          =>  $duration,
            'status'            =>  $this->status,
            'start_date'        =>  $this->start_date ? date('d-m-Y', strtotime($this->start_date)) : null,
            'end_date'          =>  $this->end_date ? date('d-m-Y', strtotime($this->end_date)) : null,
            'is_published'            =>  $this->is_published,
            'published_at'            =>  $this->published_at ? date('d-m-Y', strtotime($this->published_at)) : null,
        ];
    }
}
