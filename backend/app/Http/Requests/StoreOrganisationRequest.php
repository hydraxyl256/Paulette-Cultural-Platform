<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganisationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->hasRole('super_admin');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:100|unique:organisations,slug',
            'description' => 'nullable|string|max:1000',
            'logo_path' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'supported_languages' => 'nullable|json',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Organisation name is required.',
            'slug.required' => 'Slug is required.',
            'slug.unique' => 'This slug is already in use.',
        ];
    }
}
