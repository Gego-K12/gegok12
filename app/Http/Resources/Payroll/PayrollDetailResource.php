<?php

namespace App\Http\Resources\Payroll;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollDetailResource extends JsonResource
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
        $start_date = date('Y-m-d H:i:s', strtotime($this->start_date));
        $end_date = date('Y-m-d H:i:s', strtotime($this->end_date));
        $days = Carbon::parse($start_date)->diffInDays(Carbon::parse($end_date));

        return [

            'id' => $this->id,
            'staff_id' => $this->staff_id,
            'staffname' => $this->user->FullName,
            'designation' => $details['designation'],
            'designation_name' => $details['designation_name'],
            'sub_designation' => $details['sub_designation'],
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'percentage' => $this->percentage,
            'leave' => $this->leave,
            'late' => $this->late,
            'leave_deduction' => $this->leave_deduction,
            'comments' => $this->comments,
            'gross_salary' => $this->salary->gross_salary,
            'total_salary' => $this->salarypercentage(),
            'earning' => $this->totalearnings(),
            'deduction' => $this->totaldeductions(),
            'total' => $this->totalsalary(),
            'totaldays' => $days + 1,
            'per_day_salary' => $this->getDaySalary(),
            'leave_days' => $this->getLeaveDays($this->id, $this->start_date, $this->end_date),
            'payslipitems' => PayslipItemResource::collection($this->payslipitems),

        ];
    }
}
