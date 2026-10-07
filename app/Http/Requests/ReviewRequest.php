<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'ثبت امتیاز الزامی است.',
            'rating.min' => 'حداقل امتیاز ۱ ستاره می‌باشد.',
            'rating.max' => 'حداکثر امتیاز ۵ ستاره می‌باشد.',
            'title.required' => 'عنوان نظر الزامی است.',
            'body.required' => 'متن نظر الزامی است.',
        ];
    }
}
