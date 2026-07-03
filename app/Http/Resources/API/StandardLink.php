<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StandardLink extends JsonResource
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
            /*  'id'                =>  $this->id,
            'standard_name'     =>  $this->StandardName,
            'section_name'      =>  $this->section->name,*/
            'standard_section' => $this->StandardSection,
        ];
    }
}
