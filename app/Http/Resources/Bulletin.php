<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Bulletin extends JsonResource
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
            'name' => $this->name,
            'year' => $this->year,
            'cover_image' => $this->ImagePath,
            'path' => $this->FilePath,
        ];
    }
}
