<?php

namespace App\Http\Resources\Payroll;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollListResource extends JsonResource
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
            'id' => $this->id,
            'payrollno' => $this->payrollno,
            'staff_id' => $this->staff_id,
            'staffname' => $this->user->FullName,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            // 'comments'=>$this->comments,
            'earning' => $this->totalearnings(),
            'deduction' => $this->totaldeductions(),
            'total' => $this->totalsalary(),

        ];
    }
}
