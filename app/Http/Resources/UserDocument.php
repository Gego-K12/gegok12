<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDocument extends JsonResource
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
            'type' => str_replace('_', ' ', ucwords($this->type)),
            'name' => ucwords($this->name),
            'path' => $this->Path,
        ];
    }
}
