<?php

namespace App\Http\Controllers;

use App\Actions\Users\CreateUserAction;
use App\Actions\Users\DeleteUserAction;
use App\Actions\Users\UpdateUserAction;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function index(): Response
    {
        Gate::authorize('viewAny', User::class);

        $users = $this->userRepository->getAllPaginated();
        $roles = Role::pluck('name');

        return Inertia::render('Users/Index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => request()->only('filter', 'sort'),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', User::class);

        return Inertia::render('Users/Create', [
            'roles' => Role::pluck('name'),
        ]);
    }

    public function store(StoreUserRequest $request, CreateUserAction $action): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $action->execute($request->toDto());

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user): Response
    {
        Gate::authorize('view', $user);

        $user->load('roles');

        return Inertia::render('Users/Show', [
            'user' => $user,
        ]);
    }

    public function edit(User $user): Response
    {
        Gate::authorize('update', $user);

        $user->load('roles');

        return Inertia::render('Users/Edit', [
            'user' => $user,
            'roles' => Role::pluck('name'),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $action): RedirectResponse
    {
        Gate::authorize('update', $user);

        $action->execute($user, $request->toDto());

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user, DeleteUserAction $action): RedirectResponse
    {
        Gate::authorize('delete', $user);

        $action->execute($user);

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
