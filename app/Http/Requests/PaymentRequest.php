<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
                    'registration_id' => 'required|exists:registrations,id',
                    'amount' => 'required|numeric|min:0.01',
                    'payment_method' => 'required|string|in:card,paypal',
                    'transaction_id' => 'required|string|unique:payments,transaction_id',
                    'status' => 'required|string|in:pending,completed,failed',
                    'paypal_order_id' => 'nullable|string',
                ];
            case 'PUT':
            case 'PATCH':
                return [
                    'registration_id' => 'required|exists:registrations,id',
                    'amount' => 'required|numeric|min:0.01',
                    'payment_method' => 'required|string|in:card,paypal',
                    'transaction_id' => 'required|string|unique:payments,transaction_id,'.$this->payment,
                    'status' => 'required|string|in:pending,completed,failed',
                    'paypal_order_id' => 'nullable|string',
                ];
        }
    }
}