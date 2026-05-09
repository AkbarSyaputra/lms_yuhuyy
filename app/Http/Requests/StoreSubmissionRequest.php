<?php

namespace App\Http\Requests;

use App\DTOs\SubmissionData;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled by Policy
    }

    public function rules(): array
    {
        return [
            'content' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip', 'max:10240'], // Max 10MB
        ];
    }

    public function toDto(): SubmissionData
    {
        return SubmissionData::from([
            'content' => $this->input('content'),
            'file' => $this->file('file'),
        ]);
    }
}
