<?php

namespace App\Http\Resources\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SendMail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            //
            'subject' => $this->subject,
            'message' => $this->message,
            'sentAt' => Carbon::parse($this->fired_at)->diffForHumans(), // $this->fired_at->diffForHumans(),
        ];
    }
}
