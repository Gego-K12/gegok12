<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class School extends JsonResource
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
            'name' => ucwords($this->name),
            'slug' => $this->slug,
            'board' => $this->schoolDetailBoard->meta_value == '-' ? null : $this->schoolDetailBoard->meta_value,
        ];
    }
}
