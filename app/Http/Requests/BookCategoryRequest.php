<?php

namespace App\Http\Requests;

use App\Models\BookCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class BookCategoryRequest extends FormRequest
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

        Validator::extend('check_name', function ($attribute, $value, $parameters, $validator) {

            $category = BookCategory::where('category', request('category'))->exists();

            if ($category) {

                return false;
            }

            return true;

        });

        return [

            'category' => 'required|check_name|max:100',

        ];
    }

    public function messages()
    {
        return [

            'category.required' => 'Category is required',
            'category.check_name' => 'Already Exists',

        ];
    }
}
