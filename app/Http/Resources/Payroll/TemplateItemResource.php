<?php

namespace App\Http\Resources\Payroll;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateItemResource extends JsonResource
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
            'head' => $this->payrollitem->name,
            'head_id' => $this->item_id,
            'head_key' => $this->payrollitem->key,
            'head_type' => $this->payrollitem->type,
            'category_id' => $this->paycategory_id,
            'category_value' => $this->category_value,
            'category' => $this->paycategory->name,
        ];
    }
}
