<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (optional($this->userprofile)->avatar == '') {
            $avatarpath = \Storage::url('uploads/admin/member/avatar/images.jpg');
        } else {
            $avatarpath = $this->userprofile->AvatarPath;
        }
        if (($this->studentAcademicLatest->standardLink->standard->name == '10') || ($this->studentAcademicLatest->standardLink->standard->name == '12')) {
            $board_registration_number = $this->studentAcademicLatest->board_registration_number;
        } else {
            $board_registration_number = null;
        }

        return
        [
            'name' => $this->name,
            'school_name' => $this->school->name,
            'fullname' => $this->FullName,
            'gender' => $this->userprofile->gender,
            'date_of_birth' => date('d-m-Y', strtotime(optional($this->userprofile)->date_of_birth)),
            'address' => $this->userprofile->address,
            'city' => $this->userprofile->city->name,
            'state' => $this->userprofile->state->name,
            'country' => $this->userprofile->country->name,
            'pincode' => optional($this->userprofile)->pincode == '' ? null : optional($this->userprofile)->pincode,
            'email' => $this->email,
            'mobile_no' => $this->mobile_no,
            'notes' => optional($this->userprofile)->notes == '' ? null : optional($this->userprofile)->notes,
            'avatar' => $avatarpath,
            'created_at' => optional($this->userprofile)->created_at == '' ? null : date('d-m-Y H:i:s', strtotime(optional($this->userprofile)->created_at)),
            'updated_at' => optional($this->userprofile)->updated_at == '' ? null : date('d-m-Y H:i:s', strtotime(optional($this->userprofile)->updated_at)),
            'age' => date('Y') - date('Y', strtotime(optional($this->userprofile)->date_of_birth)),
            'ref_id' => $this->ref_id,
            'class' => $this->studentAcademicLatest->standardLink->StandardSection,
            'transport_mode' => ucwords(str_replace('_', ' ', $this->studentAcademicLatest->mode_of_transport)),
            'driver_name' => $this->studentAcademicLatest->transport_details['driver_name'],
            'driver_number' => $this->studentAcademicLatest->transport_details['driver_contact_number'],
            'registration_number' => $this->registration_number == null ? $this->userprofile->registration_number : $this->registration_number,
            'EMIS_number' => $this->userprofile->EMIS_number,
            'joining_date' => date('d-m-Y', strtotime($this->userprofile->joining_date)),
            'roll_number' => $this->studentAcademicLatest->roll_number,
            'id_card_number' => $this->studentAcademicLatest->id_card_number,
            'board_registration_number' => $board_registration_number,
            'librarycard_number' => $this->librarycard->library_card_no,
        ];
    }
}
