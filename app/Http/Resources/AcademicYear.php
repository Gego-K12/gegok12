<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademicYear extends JsonResource
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
            //
            'id' => $this->id,
            'name' => $this->name,
            'description' => str_limit($this->description, 25, '...'),
            'start_date' => date('d-m-Y', strtotime($this->start_date)),
            'end_date' => date('d-m-Y', strtotime($this->end_date)),
            'status' => $this->status,
            'status_display' => $this->StatusDisplay,
        ];
    }
}
