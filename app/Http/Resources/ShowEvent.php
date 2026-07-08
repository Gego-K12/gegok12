<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowEvent extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->standard_id != null) {
            $class_name = $this->standardlink->standardsection;

        } else {
            $class_name = null;
        }

        return [
            'id' => $this->id,
            'school_id' => $this->school_id,
            'standard_id' => $this->standard_id,
            'select_type' => $this->select_type,
            'batch' => $this->batch,
            'title' => $this->title,
            'description' => $this->description,
            'repeats' => $this->repeats,
            'freq' => $this->freq,
            'freq_term' => $this->freq_term,
            'location' => $this->location,
            'category' => $this->category,
            'image' => $this->ImagePath,
            'start_date' => date('d-m-Y H:i:s', strtotime($this->start_date)),
            'end_date' => date('d-m-Y H:i:s', strtotime($this->end_date)),
            'date' => date('d', strtotime($this->start_date)),
            'month' => date('M', strtotime($this->start_date)),
            'description_limit' => str_limit($this->description, 30, '...'),
            'class_name' => $class_name,

        ];
    }
}
