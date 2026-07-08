<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitorLog extends JsonResource
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
            'name' => $this->name,
            'relation' => $this->relation,
            'company_name' => $this->company_name == null ? '' : $this->company_name,
            'contact_number' => $this->contact_number,
            'address' => $this->address,
            'standardLink_id' => $this->getstandard($this->student_id),
            'student_id' => $this->student_id,
            'student_name' => $this->student->FullName,
            'user_name' => $this->user->FullName,
            'phone_number' => $this->contact_number,
            'relation_with_student' => $this->relation_with_student,
            'relation_name' => $this->relation_name,
            'number_of_visitors' => $this->number_of_visitors,
            'visiting_purpose' => $this->visiting_purpose,
            'visiting_purpose_name' => ucwords(str_replace('_', ' ', $this->visiting_purpose)),
            'employee_id' => $this->employee_id,
            'employee_name' => $this->employee->FullName,
            'employee_designation' => $this->employee->userprofile->designation,
            'date_of_visit' => $this->date_of_visit,
            'entry_time' => $this->entry_time,
            'exit_time' => $this->exit_time,
            'remark' => $this->remark,
            'visit_date' => date('Y-m-d', strtotime($this->date_of_visit)),
        ];
    }
}
