<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonPlan extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $hour = date('H', strtotime($this->duration));
        $minutes = date('i', strtotime($this->duration));
        $datetime = date('d-m-Y H:i:s', strtotime($this->created_at));
        if ($hour == '00') {
            $duration = $minutes.' minutes';
        } elseif ($minutes == '00') {
            $duration = $hour.' hours';
        } else {
            $duration = $hour.' hours '.$minutes.' minutes';
        }

        return
        [
            //
            'id' => $this->id,
            'subject' => $this->teacherlink->subject->name,
            'teacher_fullname' => $this->teacherlink->teacher->FullName,
            'unit_no' => $this->unit_no,
            'unit_name' => $this->unit_name,
            'title' => $this->title,
            'duration' => $duration,
            'datetime' => $datetime,
        ];
    }
}
