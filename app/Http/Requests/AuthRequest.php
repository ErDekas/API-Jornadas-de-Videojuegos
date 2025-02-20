<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                // Para el registro
                if ($this->is('/register')) {
                    return [
                        'name' => 'required|string|max:255',
                        'email' => 'required|string|email|unique:users',
                        'password' => 'required|string|min:6|confirmed',
                        'registration_type' => 'required|string|in:virtual,presential,student',
                        'is_admin' => 'boolean',
                    ];
                }
                // Para el login
                if ($this->is('/login')) {
                    return [
                        'email' => 'required|email',
                        'password' => 'required',
                    ];
                }
                // Para forgot password
                if ($this->is('/password/forgot')) {
                    return [
                        'email' => 'required|email'
                    ];
                }
                // Para reset password
                if ($this->is('/password/reset')) {
                    return [
                        'token' => 'required',
                        'email' => 'required|email',
                        'password' => 'required|min:6|confirmed'
                    ];
                }

                return [];
        }
    }
}