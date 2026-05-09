<?php

namespace App\DTOs;

use App\Enums\UserStatus;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UserData extends Data
{
    public function __construct(
        public string $name,
        #[Email]
        public string $email,
        #[Enum(UserStatus::class)]
        public UserStatus $status,
        public string $role,
        public string|Optional|null $phone = null,
        public string|Optional|null $bio = null,
        #[Min(8)]
        public string|Optional|null $password = null,
    ) {}
}
