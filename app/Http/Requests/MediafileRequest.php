<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MediafileRequest extends FormRequest
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
    public function rules(Request $request)
    {
        Validator::extend('file_extension', function ($attribute, $value, $parameters, $validator) {
            $extension = $value->getClientOriginalExtension();

            return $extension != '' && in_array($extension, $parameters);
        });

        Validator::extend('check_video_extension', function ($attribute, $value, $parameters, $validator) {
            $video_extension = \Session::get('video_extension');
            if ($video_extension == 'mp4') {
                return true;
            }

            return false;
        });

        Validator::extend('check_url', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/', request('url'));
        });

        $rules = [
            'media' => 'required',
            'name' => 'required',
            'description' => 'required|max:255',
            'type' => 'required',
        ];

        if (request('type') == 'video') {
            $rules['video_type'] = 'required';
            if (request('video_type') == 'url') {
                $rules['url'] = 'required|check_url';
            } else {
                $rules['uploadvideo'] = 'check_video_extension';
            }
        }

        if (request('type') == 'audio') {
            $rules['audio_type'] = 'required';
            if (request('audio_type') == 'attach') {
                $rules['audiofile'] = 'required|mimes:mp3';
            }
        }

        if (request('type') == 'image') {
            $files = $request->file('images');
            if (count($files) == 0) {
                $rules['images'] = 'required';
            } else {
                $rules['images.*'] = 'required|file_extension:jpeg,jpg,png';
            }
        }
        \Session::forget('video_extension');

        return $rules;
    }

    public function messages()
    {
        return [
            'media.required' => 'Media Category Is Required',

            'name.required' => 'Title Is Required',

            'description.required' => 'Description Is Required',
            'description.max:255' => 'Description May Not Be Greater Than 255 Characters',

            'type.required' => 'Media Type Is Required',

            'images.required' => 'Select Images Is Required',
            'images.*.file_extension' => 'File Extension Error. Select jpg,jpeg,png Files',

            'video_type.required' => 'Video Type Is Required',

            'url.required' => 'URL Is Required',

            'uploadvideo.required' => 'Upload Video Is Required',

            'audio_type.required' => 'Audio Type Is Required',

            'audiofile.required' => 'Attach Audio Is Required',
            'audiofile.mimes' => 'Select mp3 File',
        ];
    }
}
