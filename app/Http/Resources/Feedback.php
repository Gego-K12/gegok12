<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Feedback extends JsonResource
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
            'id' => $this->id,
            'to' => ucwords($this->admin->FullName),
            'last_message' => $this->feedbackMessage->last()->message,
            'created_on' => date('d-m-Y H:i:s', strtotime($this->created_at)),
            'last_reply_by' => $this->feedbackMessage->last()->user->FullName,
            'last_reply_on' => date('d-m-Y H:i:s', strtotime($this->feedbackMessage->last()->created_at)),
        ];
    }
}
