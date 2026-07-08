<?php

namespace App\Http\Requests;

use App\Models\Book;
use App\Models\LibraryCard;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class BookLendingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        Validator::extend('check_book_code', function ($attribute, $value, $parameters, $validator) {

            $code = Book::where('book_code', request('book_code_no'))->exists();

            if ($code) {

                return true;
            }

            return false;

        });

        Validator::extend('check_card_no', function ($attribute, $value, $parameters, $validator) {

            $card = LibraryCard::where('library_card_no', request('library_card_no'))->exists();

            if ($card) {

                return true;
            }

            return false;

        });

        return [

            'book_code_no' => 'required|check_book_code',
            'library_card_no' => 'required|check_card_no',
            'issue_date' => 'required',

        ];
    }

    public function messages()
    {
        return [

            'book_code_no.required' => 'Book Code is required',
            'book_code_no.check_book_code' => 'Enter Valid Book Code',
            'library_card_no.required' => 'Card No is Required',
            'library_card_no.check_card_no' => 'Enter Valid Card Number',
            'issue_date.required' => 'Select Issue Date',

        ];
    }
}
