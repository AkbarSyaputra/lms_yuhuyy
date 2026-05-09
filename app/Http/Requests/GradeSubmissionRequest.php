<?php

namespace App\Http\Requests;

use App\DTOs\GradeData;
use Illuminate\Foundation\Http\FormRequest;

class GradeSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled by Policy
    }

    public function rules(): array
    {
        return [
            'score' => ['required', 'integer', 'min:0'],
            'feedback' => ['nullable', 'string'],
        ];
    }

    public function toDto(): GradeData
    {
        return GradeData::from([
            'score' => $this->input('score'),
            'feedback' => $this->input('feedback'),
        ]);
    }
}
