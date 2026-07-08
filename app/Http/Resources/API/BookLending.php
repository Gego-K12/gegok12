<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookLending extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ((date('Y-m-d', strtotime($this->return_date)) > date('Y-m-d')) && ($this->status == 'pending')) {
            $bg_class = 'bg-red-300';
        } else {
            $bg_class = '';
        }

        return [
            'id' => $this->user[0]['id'],
            'user_name' => $this->user[0]['name'],
            'title' => $this->book[0]['title'],
            'isbn_number' => $this->book[0]['isbn_number'],
            'book_code_no' => $this->book_code_no,
            'library_card_no' => $this->library_card_no,
            'issue_date' => $this->issue_date,
            'return_date' => $this->return_date,
            'status' => $this->status,
            'date' => date('Y-m-d'),
            'bg_class' => $bg_class,
            'category' => $this->book[0]->category->category,
            'author' => $this->book[0]['author'],
        ];
    }
}
