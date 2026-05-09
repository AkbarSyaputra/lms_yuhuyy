<?php

namespace App\Http\Requests;

use App\DTOs\CategoryData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Handled by Policy
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('course_categories', 'slug')->ignore($this->category),
            ],
            'description' => ['nullable', 'string'],
            'parent_id' => [
                'nullable',
                'exists:course_categories,id',
                function ($attribute, $value, $fail) {
                    if ($value == $this->category->id) {
                        $fail('Category cannot be its own parent.');
                    }
                },
            ],
        ];
    }

    public function toDto(): CategoryData
    {
        return CategoryData::from($this->validated());
    }
}
