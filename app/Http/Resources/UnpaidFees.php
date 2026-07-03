<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnpaidFees extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->paid_amount != null) {
            $amount = $this->paid_amount;
        } else {
            $amount = $this->fee->amount;
        }

        if ($this->concession_applied != null) {
            $concession_applied = 'Concession';
        }

        return [
            'id' => $this->id,
            'fee_id' => $this->fee->id,
            'title' => $this->fee->name,
            'amount' => $amount,
            'standard' => $this->fee->standardLink->StandardSection,
            'student_name' => $this->user->name,
            'student_fullname' => $this->user->FullName,
            'concession_applied' => $concession_applied,
        ];
    }
}
