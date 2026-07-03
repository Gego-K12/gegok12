<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderq extends JsonResource
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
            'school_id' => $this->school->name,
            'product_name' => $this->product->name,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'purchased_date' => $this->purchased_date,
            'price_per_item' => $this->price_per_item,

        ];
    }
}
