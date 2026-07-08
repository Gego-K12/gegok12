<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EditEvent extends JsonResource
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

            'school_id' => $this->school_id,
            'select_type' => $this->select_type,
            'standard_id' => $this->standard_id,
            'title' => $this->title,
            'description' => $this->description,
            'repeats' => $this->repeats,
            'freq' => $this->freq,
            'freq_term' => $this->freq_term,
            'location' => $this->location,
            'category' => $this->category,
            'image' => $this->image,
            'start_date' => date('d-m-Y', strtotime($this->start_date)),
            'end_date' => date('d-m-Y', strtotime($this->end_date)),

        ];
    }
}
