<?php

namespace App\Http\Resources;

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
            'batch' => $this->batch,
            'title' => $this->title,
            'description' => $this->description,
            'repeats' => $this->repeats,
            'freq' => $this->freq,
            'freq_term' => $this->freq_term,
            'standard_id' => $this->standard_id,
            'location' => $this->location,
            'category' => $this->category,
            'organised_by' => $this->organised_by,
            'image' => $this->image,
            'start_date' => date('d-m-Y H:i:s', strtotime($this->start_date)),
            'end_date' => date('d-m-Y H:i:s', strtotime($this->end_date)),
        ];
    }
}
