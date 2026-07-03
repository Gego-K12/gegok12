<?php

namespace App\Http\Resources\Payroll;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $details = $this->user->getTeacherDetails();

        return [
            'id' => $this->id,
            'staff_id' => $this->staff_id,
            'template_id' => $this->template_id,
            'template_name' => $this->payrolltemplate->name,
            'staffname' => $this->user->FullName,
            'designation' => $details['designation'],
            'designation_name' => $details['designation_name'],
            'sub_designation' => $details['sub_designation'],
            'effective_date' => $this->effective_date,
            'gross_salary' => $this->gross_salary,
            'comments' => $this->comments,
            'earning' => $this->totalearnings(),
            'deduction' => $this->totaldeductions(),
            'total' => $this->totalsalary(),

            'created_at' => $this->created_at->format('Y-m-d H:m:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:m:s'),
            'salaryitems' => SalaryItemResource::collection($this->salaryitems),

        ];
    }
}
