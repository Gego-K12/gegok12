<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingSeoDetailRequest extends FormRequest
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
        return [
            'sitetitle' => 'required',
            'site_description' => 'required',
            'site_keyword' => 'required',
            'twitter_handle' => 'required',
            'twitter_description' => 'required',
            'twitter_card_image' => 'required',
            'facebook_site_url' => 'required',
            'facebook_card_image' => 'required',
        ];
    }

    public function messages()
    {
        return [

            'sitetitle.required' => __('sites.sitetitle'),
            'site_description.required' => __('sites.site_description'),
            'site_keyword.required' => __('sites.site_keyword'),
            'twitter_handle.required' => __('sites.twitter_handle'),
            'twitter_description.required' => __('sites.twitter_description'),
            'twitter_card_image.required' => __('sites.twitter_card'),
            'facebook_site_url.required' => __('sites.facebook_site_url'),
            'facebook_card_image.required' => __('sites.facebook_card'),
        ];
    }
}
