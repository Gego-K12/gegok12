<?php

namespace App\Http\Resources;

use App\Helpers\SiteHelper;
use App\Models\StandardLink;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserSibling extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $school_id = Auth::user()->school_id;

        $academic_year = SiteHelper::getAcademicYear($school_id);
        $details = [];
        $i = 0;
        foreach ($this->studentAcademicLatest->sibling_details as $details) {
            $array[$i]['fullname'] = ucwords($details['sibling_name']);
            $array[$i]['relation'] = ucwords($details['sibling_relation']);
            $array[$i]['date_of_birth'] = date('d-m-Y', strtotime($details['sibling_date_of_birth']));
            $standardLink = StandardLink::where([['id', $details['sibling_standard']], ['academic_year_id', $academic_year->id]])->first();
            $array[$i]['standard_section'] = $standardLink->StandardSection;
            $i++;
        }

        return $array;
    }
}
