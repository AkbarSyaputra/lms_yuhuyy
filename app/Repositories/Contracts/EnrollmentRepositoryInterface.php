<?php

namespace App\Repositories\Contracts;

use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Collection;

interface EnrollmentRepositoryInterface
{
    public function getEnrollmentsForCourse(int $courseId): Collection;

    public function getEnrollmentsForUser(int $userId): Collection;

    public function checkEnrollment(int $userId, int $courseId): ?Enrollment;

    public function enrollUser(int $userId, int $courseId): Enrollment;

    public function unenrollUser(int $userId, int $courseId): bool;
}
