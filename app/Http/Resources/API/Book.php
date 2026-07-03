<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Book extends JsonResource
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
            'title' => $this->title,
            'author' => $this->author,
            'category_id' => $this->category_id,
            'book_code' => $this->book_code,
            'isbn_number' => $this->isbn_number,
            'cover_image' => url($this->cover_image),
            'availability' => $this->availability,

        ];
    }
}
