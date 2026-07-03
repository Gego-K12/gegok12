<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLog extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $ip = $this->properties['ip'];

        return
        [
            'log_name' => $this->log_name,
            'description' => $this->description,
            'created_at' => date('d-m-Y H:i:s', strtotime($this->created_at)),
            'ip' => $ip,
        ];
    }
}
