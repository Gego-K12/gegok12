<?php

namespace App\Http\Resources\Classwall;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageCategory extends JsonResource
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
            'display_name' => ucwords(str_replace('_', ' ', $this->name)),
        ];
    }
}
