<?php

namespace App\Http\Resources\Payroll;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalaryListResource extends JsonResource
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
            'staffname' => $this->user->FullName,
            'effective_date' => $this->effective_date,
            'gross_salary' => $this->gross_salary,
            'comments' => $this->comments,
            'earning' => $this->totalearnings(),
            'deduction' => $this->totaldeductions(),
            'total' => $this->totalsalary(),
            'created_at' => $this->created_at->format('Y-m-d H:m:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:m:s'),
        ];
    }
}
