<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'group_name' => $this->group_name,
            // 'members_count'   => $this->members_count,
            'created_at' => $this->created_at?->format('d M Y')
        ];
    }
}
