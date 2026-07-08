<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Notice extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {

        if ($this->standardLink_id != null) {
            $class_name = $this->standardLink->StandardSection;
        } else {
            $class_name = '--';
        }

        if ($this->attachment_file != null) {
            $attachment_file = $this->AttachmentPath;
        } else {
            $attachment_file = '';
        }

        if ($this->background_id != null) {
            $backgroundimage = $this->backgroundimage->AttachmentPath;
            if ($backgroundimage == null) {
                $backgroundimage = '';
            }
        } else {
            $backgroundimage = '';
        }

        return
        [
            'id' => $this->id,
            'type' => ucfirst($this->type),
            'class_name' => $class_name,
            'title' => $this->title,
            'publish_date' => date('d-m-Y H:i:s', strtotime($this->publish_date)),
            'expire_date' => date('d-m-Y H:i:s', strtotime($this->expire_date)),
            'description' => $this->description,
            'backgroundimage' => $backgroundimage,
            'attachment_file' => $attachment_file,
        ];

    }
}
