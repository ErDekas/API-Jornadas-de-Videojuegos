<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
                    'title' => 'required|string|max:255',
                    'description' => 'required|string',
                    'type' => 'required|string',
                    'date' => 'required|date',
                    'start_time' => 'required',
                    'end_time' => 'required',
                    'max_attendees' => 'required|integer',
                    'current_attendees' => 'required|integer',
                    'location' => 'required|string|max:255',
                ];
        }
    }
}