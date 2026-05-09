<?php

namespace App\Actions\Users;

use App\DTOs\UserData;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Spatie\LaravelData\Optional;

class UpdateUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function execute(User $user, UserData $data): User
    {
        $userData = [
            'name' => $data->name,
            'email' => $data->email,
            'status' => $data->status,
        ];

        if (! ($data->password instanceof Optional) && ! empty($data->password)) {
            $userData['password'] = Hash::make($data->password);
        }

        if (! ($data->phone instanceof Optional)) {
            $userData['phone'] = $data->phone;
        }

        if (! ($data->bio instanceof Optional)) {
            $userData['bio'] = $data->bio;
        }

        $user = $this->userRepository->update($user, $userData);

        $user->syncRoles([$data->role]);

        return $user;
    }
}
