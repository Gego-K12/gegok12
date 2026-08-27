<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimetableEdit extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $daylist = ['1' => 'Monday' , '2' => 'Tuesday' , '3' => 'Wednesday' , '4' => 'Thursday' , '5'  => 'Friday' , '6' => 'Saturday'];
        foreach ($daylist as $key => $value) 
        {
            if($this->day == $value)
            {
                $day_name = (string)$key;
            }
        }
        
        return 
        [
            //
            'day'       =>   $day_name,
            'day_name'  =>   $this->day,
            'array'     =>   $this->schedule,
        ];
    }
}