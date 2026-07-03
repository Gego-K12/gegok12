<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassEvent extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'organised_by' => $this->organised_by ? ucfirst(trim($this->organised_by)) : null,
            'location' => $this->location,
            'image' => $this->ImagePath,
            'start_date' => date('d M Y h:i:s a', strtotime($this->start_date)),
            'end_date' => date('d M Y h:i:s a', strtotime($this->end_date)),
        ];
    }
}
