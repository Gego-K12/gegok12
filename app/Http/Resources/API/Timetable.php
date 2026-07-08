<?php

namespace App\Http\Resources\API;

use App\Models\Teacherlink;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Timetable extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $teacherlinks = Teacherlink::where('standardLink_id', $this->standardLink_id)->get();

        foreach ($teacherlinks as $teacherlink) {
            foreach ($this->schedule as $key => $schedule) {
                if ($teacherlink->subject->name == $schedule['subject_id']) {
                    $schedule['teacher'] = $teacherlink->teacher->FullName;
                    $schedule_arr[] = $schedule;
                }
            }
        }

        $daylist = ['1' => 'Monday', '2' => 'Tuesday', '3' => 'Wednesday', '4' => 'Thursday', '5' => 'Friday', '6' => 'Saturday'];
        foreach ($daylist as $key => $value) {
            if ($this->day == $value) {
                $day_name = (string) $key;
            }
        }

        return
        [
            //
            'day' => $day_name,
            'day_name' => $this->day,
            'array' => $schedule_arr == null ? [] : $schedule_arr,
        ];
    }
}
