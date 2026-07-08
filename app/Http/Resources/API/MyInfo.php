<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MyInfo extends JsonResource
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
            'fullname' => $this->FullName,
            'mobileNo' => $this->mobile_no,
            'email' => $this->email,
            'alternateNo' => $this->userprofile->alternate_no,
            'occupation' => ucwords(str_replace('_', ' ', $this->getParentDetails()['profession'])),
            'subOccupation' => ucfirst($this->getParentDetails()['sub_occupation']),
            'designation' => ucfirst($this->getParentDetails()['designation']),
            'organizationName' => ucfirst($this->getParentDetails()['organization_name']),
            'officialAddress' => $this->getParentDetails()['official_address'],
            'qualification' => $this->getParentDetails()['qualification_name'],
            'annualIncome' => $this->getParentDetails()['annual_income'],
        ];
    }
}
