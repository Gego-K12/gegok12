<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeePayment extends JsonResource
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
            'id' => $this->id,
            'name' => $this->fee->name,
            'term' => $this->fee->term,
            'amount' => $this->fee->amount,
            'dueDate' => date('d M Y', strtotime($this->fee->end_date)),
            'paidOn' => date('d M Y', strtotime($this->paid_on)),
        ];
    }
}
