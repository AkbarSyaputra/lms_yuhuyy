<?php

use App\Actions\Users\CreateUserAction;
use App\DTOs\UserData;
use App\Enums\UserStatus;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

it('can create a new user with role', function () {
    $repository = new UserRepository;
    $action = new CreateUserAction($repository);

    // Ensure role exists for testing
    Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);

    $data = new UserData(
        name: 'Test Teacher',
        email: 'teacher.test@eduzy.test',
        status: UserStatus::Active,
        role: 'teacher',
        phone: '08123456789',
        bio: 'I am a test teacher.',
        password: 'password123'
    );

    $user = $action->execute($data);

    expect($user)->toBeInstanceOf(User::class)
        ->name->toBe('Test Teacher')
        ->email->toBe('teacher.test@eduzy.test')
        ->status->toBe(UserStatus::Active)
        ->phone->toBe('08123456789')
        ->bio->toBe('I am a test teacher.');

    expect(Hash::check('password123', $user->password))->toBeTrue();

    expect($user->hasRole('teacher'))->toBeTrue();
});
