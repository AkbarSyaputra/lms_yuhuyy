<?php

use App\Enums\UserStatus;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('allows admin to view users list', function () {
    actingAsAdmin();
    $this->get(route('users.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Users/Index')
            ->has('users')
            ->has('roles')
        );
});

it('forbids non-admin from viewing users list', function () {
    actingAsTeacher();
    $this->get(route('users.index'))
        ->assertForbidden();

    actingAsStudent();
    $this->get(route('users.index'))
        ->assertForbidden();
});

it('allows admin to create user', function () {
    $admin = actingAsAdmin();

    $response = $this->post(route('users.store'), [
        'name' => 'New Student',
        'email' => 'new.student@eduzy.test',
        'password' => 'password123',
        'role' => 'student',
        'status' => UserStatus::Active->value,
    ]);

    $response->assertRedirect(route('users.index'));

    $this->assertDatabaseHas('users', [
        'email' => 'new.student@eduzy.test',
        'name' => 'New Student',
        'status' => UserStatus::Active->value,
    ]);

    $user = User::where('email', 'new.student@eduzy.test')->first();
    expect($user->hasRole('student'))->toBeTrue();
});

it('allows admin to update user', function () {
    actingAsAdmin();
    $user = User::factory()->create();

    $response = $this->put(route('users.update', $user), [
        'name' => 'Updated Name',
        'email' => 'updated@eduzy.test',
        'role' => 'teacher',
        'status' => UserStatus::Inactive->value,
        // password is empty/nullable in update
    ]);

    $response->assertRedirect(route('users.index'));

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'email' => 'updated@eduzy.test',
        'name' => 'Updated Name',
        'status' => UserStatus::Inactive->value,
    ]);
});

it('allows admin to delete another user', function () {
    actingAsAdmin();
    $user = User::factory()->create();

    $response = $this->delete(route('users.destroy', $user));

    $response->assertRedirect(route('users.index'));
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

it('prevents admin from deleting themselves', function () {
    $admin = actingAsAdmin();

    $response = $this->delete(route('users.destroy', $admin));

    $response->assertForbidden();
    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});
