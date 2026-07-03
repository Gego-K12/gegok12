<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Subscription extends JsonResource
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
            // 'user_id' => $this->user_id,
            'school' => $this->school->name,
            'plan' => $this->plan->name,
            'plan_days' => $this->plan->cycle,
            'amount' => $this->payment_details['amount'],
            'end_date' => date('d-m-Y', strtotime($this->end_date)),
        ];
    }
}
