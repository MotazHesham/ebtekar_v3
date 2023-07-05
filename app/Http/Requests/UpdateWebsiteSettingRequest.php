<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateWebsiteSettingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('website_setting_edit');
    }

    public function rules()
    {
        return [
            'logo' => [
                'required',
            ],
            'site_name' => [
                'string',
                'required',
            ],
            'domains' => [ 
                'required',
            ],
            'description_seo' => [ 
                'required',
            ],
            'keywords_seo' => [ 
                'required',
            ],
            'author_seo' => [ 
                'required',
            ],
            'sitemap_link_seo' => [ 
                'required',
            ],
            'phone_number' => [
                'string',
                'nullable',
            ],
            'facebook' => [
                'string',
                'nullable',
            ],
            'instagram' => [
                'string',
                'nullable',
            ],
            'twitter' => [
                'string',
                'nullable',
            ],
            'telegram' => [
                'string',
                'nullable',
            ],
            'linkedin' => [
                'string',
                'nullable',
            ],
            'whatsapp' => [
                'string',
                'nullable',
            ],
            'youtube' => [
                'string',
                'nullable',
            ],
            'google_plus' => [
                'string',
                'nullable',
            ],
            'photos' => [
                'array',
            ],
            'video_instructions' => [
                'string',
                'nullable',
            ],
        ];
    }
}
