<?php

namespace App\Policies;

use App\Models\Material;
use App\Models\User;

class MaterialPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('materials.view');
    }

    public function view(User $user, Material $material): bool
    {
        return $user->can('materials.view');
    }

    public function create(User $user): bool
    {
        return $user->can('materials.create');
    }

    public function update(User $user, Material $material): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->can('materials.update') && $material->course->created_by === $user->id;
    }

    public function delete(User $user, Material $material): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->can('materials.delete') && $material->course->created_by === $user->id;
    }
}
