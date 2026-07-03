<?php

namespace App\Http\Resources\Payroll;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayslipItemResource extends JsonResource
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
            'salary_item' => $this->salary_item_id,
            'amount' => $this->amount,
            'head_key' => $this->salaryitem->templateitem->payrollitem->key,
            'head' => $this->salaryitem->templateitem->payrollitem->name,
            'head_type' => $this->salaryitem->templateitem->payrollitem->type,
            'head_key' => $this->salaryitem->templateitem->payrollitem->key,
            'category' => $this->salaryitem->templateitem->paycategory->name,
            'category_value' => $this->salaryitem->templateitem->paycategory->name,

        ];
    }
}
