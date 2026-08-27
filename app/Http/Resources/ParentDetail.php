<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParentDetail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Get the first child (primary student) using eager-loaded relationships
        $child = null;
        if ($this->children && count($this->children) > 0) {
            $firstChild = $this->children->first();
            $student = $firstChild->userStudent;

            // Use the eager-loaded studentAcademic relationship
            // The relationship is loaded with ->limit(1) to get the latest
            $studentAcademic = $student->studentAcademic->first() ?? null;

            $standardLink = $studentAcademic?->standardLink;
            $standard = $standardLink?->standard;
            $section = $standardLink?->section;

            $child = [
                'name' => url('/admin/student/show/'.$student->name),
                'fullname' => $student->FullName,
                'roll_number' => $studentAcademic?->roll_number ?? 'N/A',
                'standard' => $standard?->name ?? 'N/A',
                'section' => $section?->name ?? 'N/A',
            ];
        }

        // Get parent gender for Mr/Mrs prefix (already eager-loaded in query)
        $gender = $this->userprofile?->gender ?? null;
        $prefix = '';
        if ($gender) {
            $prefix = strtolower($gender) === 'male' ? 'Mr.' : 'Mrs.';
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile_no' => $this->mobile_no,
            'fullname' => $this->FullName,
            'prefix' => $prefix,
            'gender' => $gender,
            'children' => $child,
            'editurl' => url('/admin/parent/edit/'.$this->name),
            'showurl' => url('/admin/parent/show/'.$this->name),
            'status' => $this->status,
        ];
    }
}
