<?php

use App\Actions\Users\UpdateUserAction;
use App\DTOs\UserData;
use App\Enums\UserStatus;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
});

it('can update user details and role', function () {
    $repository = new UserRepository;
    $action = new UpdateUserAction($repository);

    $user = User::factory()->create();
    $user->assignRole('admin');

    $data = new UserData(
        name: 'Updated Name',
        email: 'updated@eduzy.test',
        status: UserStatus::Inactive,
        role: 'teacher',
        phone: '0987654321',
        bio: 'Updated bio.',
        password: 'newpassword123'
    );

    $updatedUser = $action->execute($user, $data);

    expect($updatedUser)
        ->id->toBe($user->id)
        ->name->toBe('Updated Name')
        ->email->toBe('updated@eduzy.test')
        ->status->toBe(UserStatus::Inactive)
        ->phone->toBe('0987654321')
        ->bio->toBe('Updated bio.');

    expect(Hash::check('newpassword123', $updatedUser->password))->toBeTrue();
    expect($updatedUser->hasRole('teacher'))->toBeTrue();
    expect($updatedUser->hasRole('admin'))->toBeFalse();
});

it('does not update password if not provided', function () {
    $repository = new UserRepository;
    $action = new UpdateUserAction($repository);

    $originalPassword = Hash::make('oldpassword');
    $user = User::factory()->create(['password' => $originalPassword]);
    $user->assignRole('teacher');

    $data = new UserData(
        name: 'Same Password',
        email: 'same@eduzy.test',
        status: UserStatus::Active,
        role: 'teacher',
        phone: null,
        bio: null,
        password: null // No password update
    );

    $updatedUser = $action->execute($user, $data);

    expect(Hash::check('oldpassword', $updatedUser->password))->toBeTrue();
});
