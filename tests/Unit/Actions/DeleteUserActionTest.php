<?php

use App\Actions\Users\DeleteUserAction;
use App\Models\User;
use App\Repositories\UserRepository;

it('can delete a user', function () {
    $repository = new UserRepository;
    $action = new DeleteUserAction($repository);

    $user = User::factory()->create();

    $result = $action->execute($user);

    expect($result)->toBeTrue();
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});
