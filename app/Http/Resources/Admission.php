<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Admission extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'standard_id' => $this->standard->present()->integerToRoman($this->standard->name),
            'application_no' => $this->application_no,
            'application_status' => $this->application_status,
        ];
    }
}
