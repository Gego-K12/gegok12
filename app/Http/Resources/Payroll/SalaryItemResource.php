<?php

namespace App\Http\Resources\Payroll;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalaryItemResource extends JsonResource
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
            'template_item' => $this->template_item_id,
            'head' => $this->templateitem->payrollitem->name,
            'head_id' => $this->templateitem->item_id,
            'category_id' => $this->templateitem->paycategory_id,
            'head_type' => $this->templateitem->payrollitem->type,
            'head_key' => $this->templateitem->payrollitem->key,
            'category' => $this->templateitem->paycategory->name,
            'category_value' => $this->templateitem->category_value,
            'amount' => $this->amount,

        ];
    }
}
