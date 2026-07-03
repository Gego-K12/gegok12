<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowMark extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = [];
        /* if($this->student_status == 'absent')
         {
             $array['studentStatus'] = ucfirst($this->student_status);
         }
         else
         {*/
        // $array['subject']           = ucwords($this->subject->name);
        $array[ucwords($this->subject->name)] = $this->obtained_marks;

        /* $array['grade']             = ucwords($this->grade->name);

         //$array['totalMarks']        = $this->total_marks;
         $array['teacherComments']   = $this->teacher_comment;
         $array['comments']          = $this->comments;
         $array['studentStatus']     = ucfirst($this->student_status);
         $array['gradesAwarded']     = $this->grades_awarded;*/
        //  }
        return $array;
    }
}
