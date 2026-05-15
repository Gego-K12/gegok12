<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskAssignee extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'user_id' => $this->user_id,

            'user_name' => optional($this->user)->name,

            'full_name' => optional($this->user)->FullName,

            'status' => $this->status,

            'assigned_type' => $this->assigned_type,

            'group_id' => $this->group_id,

            'standardLink_id' => $this->standardLink_id,

            'claimed_by' => $this->claimed_by,
        ];

    }
}
