<?php

namespace App\Http\Requests;

use App\DTOs\CourseData;
use App\Enums\CourseStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Handled by Policy
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('courses', 'slug')->ignore($this->course),
            ],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:course_categories,id'],
            'status' => ['required', new Enum(CourseStatus::class)],
            'max_students' => ['nullable', 'integer', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }

    public function toDto(): CourseData
    {
        return CourseData::from($this->validated());
    }
}
