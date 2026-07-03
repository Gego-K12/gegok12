<?php

namespace App\Http\Resources\API;

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
            'name' => $this->name,
            'profession' => optional($this->userprofile)->profession,
            'avatar' => optional($this->userprofile)->avatar,
            'fullname' => $this->FullName,
        ];
    }
}
