<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpeakerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
            case 'PUT':
            case 'PATCH':
                return [
                    'name' => 'required|string|max:255',
                    'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                    'social_links' => 'nullable',
                    'social_links.*' => 'nullable|url',
                    'expertise_areas' => 'nullable|array',
                ];
        }
    }
}