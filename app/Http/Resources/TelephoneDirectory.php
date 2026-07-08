<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TelephoneDirectory extends JsonResource
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
            'id' => $this->id,
            'school_id' => $this->school_id,
            'name' => ucfirst($this->name),
            'designation' => ucfirst($this->designation),
            'phone_number' => $this->phone_number,
            'type' => 'telephone_directory',

        ];
    }
}
