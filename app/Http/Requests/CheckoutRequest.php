<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'address_id' => ['required', 'exists:addresses,id'],
            'shipping_method' => ['required', 'string', 'in:standard,express'],
            'payment_method' => ['required', 'string', 'in:test_gateway,zarinpal,wallet'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'address_id.required' => 'انتخاب آدرس تحویل سفارش الزامی است.',
            'shipping_method.required' => 'انتخاب شیوه ارسال الزامی است.',
            'payment_method.required' => 'انتخاب روش پرداخت الزامی است.',
        ];
    }
}
