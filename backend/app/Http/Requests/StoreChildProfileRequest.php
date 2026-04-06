<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChildProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'avatar_color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'profile_icon' => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Child name is required.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
            'avatar_color.regex' => 'Avatar color must be a valid hex color.',
        ];
    }
}
