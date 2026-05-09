<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class EnrollmentPolicy
{
    /**
     * Determine whether the user can enroll in the given course.
     */
    public function enroll(User $user, Course $course): bool
    {
        // Only students can enroll
        if (! $user->hasRole('student')) {
            return false;
        }

        // Can only enroll in published courses
        if ($course->status->value !== 'published') {
            return false;
        }

        return true;
    }
}
