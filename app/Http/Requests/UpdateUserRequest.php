<?php

namespace App\Http\Requests;

use App\DTOs\UserData;
use App\Enums\UserStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled by Policy
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user)],
            'password' => ['nullable', 'string', Password::defaults()],
            'role' => ['required', 'string', 'exists:roles,name'],
            'status' => ['required', new Enum(UserStatus::class)],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string'],
        ];
    }

    public function toDto(): UserData
    {
        return UserData::from($this->validated());
    }
}
