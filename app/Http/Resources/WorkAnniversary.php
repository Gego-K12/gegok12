<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkAnniversary extends JsonResource
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
            'years' => $this->present()->getAge($this['joining_date']),
            'name' => $this->user->name,
            'avatar' => $this->AvatarPath,
            'firstname' => $this->user->FullName,
        ];
    }
}
