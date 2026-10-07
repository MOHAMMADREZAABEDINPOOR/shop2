<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:100'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'recipient_phone' => ['required', 'string', 'regex:/^09[0-9]{9}$/'],
            'province' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'regex:/^[0-9]{10}$/'],
            'address_line' => ['required', 'string', 'max:1000'],
            'plaque' => ['nullable', 'string', 'max:20'],
            'unit' => ['nullable', 'string', 'max:20'],
            'is_default' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان آدرس الزامی است.',
            'recipient_name.required' => 'نام گیرنده الزامی است.',
            'recipient_phone.required' => 'شماره موبایل گیرنده الزامی است.',
            'recipient_phone.regex' => 'فرمت شماره موبایل گیرنده نامعتبر است.',
            'province.required' => 'انتخاب استان الزامی است.',
            'city.required' => 'انتخاب شهر الزامی است.',
            'postal_code.required' => 'کد پستی ۱۰ رقمی الزامی است.',
            'postal_code.regex' => 'کد پستی باید دقیقاً ۱۰ رقم باشد.',
            'address_line.required' => 'نشانی دقیق پستی الزامی است.',
        ];
    }
}
