<?php

namespace App\Http\Requests;

use App\DTOs\MaterialData;
use App\Enums\MaterialType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::enum(MaterialType::class)],
            'content' => ['nullable', 'string'],
            'file_path' => ['nullable', 'string', 'max:500'],
            'external_url' => ['nullable', 'url', 'max:500'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['boolean'],
        ];
    }

    public function toDto(): MaterialData
    {
        return MaterialData::from($this->validated());
    }
}
