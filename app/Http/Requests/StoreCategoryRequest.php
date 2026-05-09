<?php

namespace App\Http\Requests;

use App\DTOs\CategoryData;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Handled by Policy
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:course_categories,slug'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:course_categories,id'],
        ];
    }

    public function toDto(): CategoryData
    {
        return CategoryData::from($this->validated());
    }
}
