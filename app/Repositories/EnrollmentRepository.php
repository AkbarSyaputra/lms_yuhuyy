<?php

namespace App\Repositories;

use App\Enums\EnrollmentStatus;
use App\Models\Enrollment;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function getEnrollmentsForCourse(int $courseId): Collection
    {
        return Enrollment::with('user')
            ->where('course_id', $courseId)
            ->latest('enrolled_at')
            ->get();
    }

    public function getEnrollmentsForUser(int $userId): Collection
    {
        return Enrollment::with('course')
            ->where('user_id', $userId)
            ->latest('enrolled_at')
            ->get();
    }

    public function checkEnrollment(int $userId, int $courseId): ?Enrollment
    {
        return Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();
    }

    public function enrollUser(int $userId, int $courseId): Enrollment
    {
        return Enrollment::create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'status' => EnrollmentStatus::Active,
            'enrolled_at' => now(),
        ]);
    }

    public function unenrollUser(int $userId, int $courseId): bool
    {
        return Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->delete() > 0;
    }
}
