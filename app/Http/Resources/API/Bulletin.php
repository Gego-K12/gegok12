<?php

namespace App\Http\Resources\API;

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

        return [
            'id' => $this->id,
            'schoolId' => $this->school_id,
            'name' => $this->name,
            'year' => $this->year,
            'coverImage' => $this->ImagePath,
            'path' => $this->FilePath,
        ];
    }
}
