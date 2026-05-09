<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;

class AssignmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('assignments.view');
    }

    public function view(User $user, Assignment $assignment): bool
    {
        return $user->can('assignments.view');
    }

    public function create(User $user): bool
    {
        return $user->can('assignments.create');
    }

    public function update(User $user, Assignment $assignment): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->can('assignments.update') && $assignment->course->created_by === $user->id;
    }

    public function delete(User $user, Assignment $assignment): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->can('assignments.delete') && $assignment->course->created_by === $user->id;
    }
}
