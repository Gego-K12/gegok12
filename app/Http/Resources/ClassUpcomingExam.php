<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassUpcomingExam extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return
        [
            //
            'exam_id' => $this->exam_id,
            'exam_name' => $this->exam->name,
            'subject_name' => $this->subject->name,
            'start_time' => date('d M Y h:i:s a', (strtotime($this->start_time))),
        ];
    }
}
