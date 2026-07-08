<?php

namespace App\Http\Resources\Demo;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'user_name' => $this->name,
            'fullname' => $this->FullName,
            'email' => $this->email,
            'mobile_no' => $this->mobile_no,
        ];
    }
}
