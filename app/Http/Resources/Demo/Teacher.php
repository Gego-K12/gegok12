<?php

namespace App\Http\Resources\Demo;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Teacher extends JsonResource
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
            'id' => $this->user->id,
            'user_name' => $this->user->name,
            'fullname' => $this->user->FullName,
            'email' => $this->user->email,
            'mobile_no' => $this->user->mobile_no,
        ];
    }
}
