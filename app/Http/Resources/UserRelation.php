<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserRelation extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->userParent->getParentDetails()['profession'] == null) {
            $profession = '--';
        } else {
            $profession = ucwords(str_replace('_', ' ', $this->userParent->getParentDetails()['profession']));
        }

        return
        [
            //
            'name' => $this->userParent->name,
            'fullname' => $this->userParent->FullName,
            'mobile_no' => $this->userParent->mobile_no,
            'email' => $this->userParent->email == null ? '--' : $this->userParent->email,
            'relation' => ucfirst($this->userParent->getParentDetails()['relation']),
            'profession' => $profession,
            'sub_occupation' => ucwords($this->userParent->getParentDetails()['sub_occupation']),
            'status' => $this->userParent->status,
        ];
    }
}
