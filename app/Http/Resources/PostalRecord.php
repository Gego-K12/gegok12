<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostalRecord extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {

        if ($this->attachment != null) {
            $attachment = $this->AttachmentPath;
        } else {
            $attachment = '';
        }

        if ($this->type == 'dispatch') {
            $address = $this->sender_title.$this->sender_address;
        } else {
            $address = $this->receiver_title.$this->receiver_address;
        }

        return [

            'id' => $this->id,
            'school_id' => $this->school_id,
            'academic_year_id' => $this->academic_year_id,
            'type' => $this->type,
            'reference_number' => $this->reference_number,
            'confidential' => $this->confidential,
            'address' => $address,
            'sender_title' => $this->sender_title,
            'sender_address' => $this->sender_address,
            'sender' => $this->sender_title.$this->sender_address,
            'receiver_title' => $this->receiver_title,
            'receiver_address' => $this->receiver_address,
            'receiver' => $this->receiver_title.$this->receiver_address,

            'postal_date' => $this->postal_date,
            'attachment' => $attachment,
            'description' => $this->description,
            'entry_by' => $this->entry_by,

        ];
    }
}
