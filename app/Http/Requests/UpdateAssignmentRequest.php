<?php

namespace App\Http\Requests;

use App\DTOs\AssignmentData;
use App\Enums\AssignmentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::enum(AssignmentType::class)],
            'description' => ['nullable', 'string'],
            'max_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'due_date' => ['nullable', 'date'],
            'is_published' => ['boolean'],
        ];
    }

    public function toDto(): AssignmentData
    {
        return AssignmentData::from($this->validated());
    }
}
