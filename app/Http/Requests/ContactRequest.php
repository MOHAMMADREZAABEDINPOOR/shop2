<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            // Honeypot: bots fill it, humans never see it.
            'website' => ['nullable', 'max:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'نام خود را وارد کنید.',
            'email.required' => 'ایمیل خود را وارد کنید.',
            'email.email' => 'ایمیل نامعتبر است.',
            'subject.required' => 'موضوع پیام الزامی است.',
            'message.required' => 'متن پیام الزامی است.',
        ];
    }
}
