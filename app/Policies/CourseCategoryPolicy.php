<?php

namespace App\Policies;

use App\Models\CourseCategory;
use App\Models\User;

class CourseCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('categories.view');
    }

    public function view(User $user, CourseCategory $category): bool
    {
        return $user->can('categories.view');
    }

    public function create(User $user): bool
    {
        return $user->can('categories.create');
    }

    public function update(User $user, CourseCategory $category): bool
    {
        return $user->can('categories.update');
    }

    public function delete(User $user, CourseCategory $category): bool
    {
        return $user->can('categories.delete');
    }
}
