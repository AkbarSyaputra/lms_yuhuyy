<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\Submission;
use App\Models\User;

class SubmissionPolicy
{
    /**
     * Determine whether the user can view the submission.
     */
    public function view(User $user, Submission $submission): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Student can view their own submission
        if ($user->id === $submission->student_id) {
            return true;
        }

        // Teacher can view submissions for their courses
        if ($user->hasRole('teacher')) {
            return $user->id === $submission->assignment->course->created_by;
        }

        return false;
    }

    /**
     * Determine whether the user can create a submission for the given assignment.
     */
    public function create(User $user, Assignment $assignment): bool
    {
        if (! $user->hasRole('student')) {
            return false;
        }

        // Check if student is enrolled in the course
        $isEnrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $assignment->course_id)
            ->exists();

        if (! $isEnrolled) {
            return false;
        }

        // Check if assignment is published
        if (! $assignment->is_published) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can grade the submission.
     */
    public function grade(User $user, Submission $submission): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Only the teacher who owns the course can grade
        if ($user->hasRole('teacher')) {
            return $user->id === $submission->assignment->course->created_by;
        }

        return false;
    }
}
