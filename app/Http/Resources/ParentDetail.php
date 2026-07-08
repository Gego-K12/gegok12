<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParentDetail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        foreach ($this->children as $children) {
            $child['name'] = url('/admin/student/show/'.$children->userStudent->name);
            $child['fullname'] = $children->userStudent->FullName;
        }

        return
        [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile_no' => $this->mobile_no,
            'fullname' => $this->FullName,
            'children' => $child,
            'editurl' => url('/admin/parent/edit/'.$this->name),
            'showurl' => url('/admin/parent/show/'.$this->name),
            'status' => $this->status,
        ];
    }
}
