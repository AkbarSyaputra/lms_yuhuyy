<?php

namespace App\Actions\Users;

use App\DTOs\UserData;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Spatie\LaravelData\Optional;

class CreateUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function execute(UserData $data): User
    {
        $userData = [
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
            'status' => $data->status,
            'phone' => $data->phone instanceof Optional ? null : $data->phone,
            'bio' => $data->bio instanceof Optional ? null : $data->bio,
        ];

        $user = $this->userRepository->create($userData);

        $user->assignRole($data->role);

        return $user;
    }
}
