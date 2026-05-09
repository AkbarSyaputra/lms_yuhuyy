<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Global Helpers
|--------------------------------------------------------------------------
*/

function actingAsAdmin(): User
{
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

    $user = User::factory()->create();
    $user->assignRole('admin');
    test()->actingAs($user);

    return $user;
}

function actingAsTeacher(): User
{
    Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole('teacher');
    test()->actingAs($user);

    return $user;
}

function actingAsStudent(): User
{
    Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole('student');
    test()->actingAs($user);

    return $user;
}
