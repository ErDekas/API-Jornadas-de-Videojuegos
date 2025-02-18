<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'user_id' => 'required|exists:users,id',
                    'registration_type' => 'required|string|max:255',
                    'total_amount' => 'required|numeric|min:0.01',
                    'payment_status' => 'required|string|in:pending,completed,failed',
                    'ticket_code' => 'required|string|max:255|unique:registrations,ticket_code',
                ];
            case 'PUT':
            case 'PATCH':
                return [
                    'user_id' => 'required|exists:users,id',
                    'registration_type' => 'required|string|max:255',
                    'total_amount' => 'required|numeric|min:0.01',
                    'payment_status' => 'required|string|in:pending,completed,failed',
                    'ticket_code' => 'required|string|max:255|unique:registrations,ticket_code,'.$this->registration,
                ];
        }
    }
}