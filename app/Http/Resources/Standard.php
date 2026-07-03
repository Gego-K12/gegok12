<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Standard extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (($this->name == 'PREKG') || ($this->name == 'prekg')) {
            $standard_name = 'PREKG';
        } elseif (($this->name == 'LKG') || ($this->name == 'lkg')) {
            $standard_name = 'LKG';
        } elseif (($this->name == 'UKG') || ($this->name == 'ukg')) {
            $standard_name = 'UKG';
        } else {
            $standard_name = $this->present()->integerToRoman($this->name);
        }

        return [
            //
            'id' => $this->id,
            'name' => $standard_name, // $this->present()->integerToRoman($this->name),
        ];
    }
}
