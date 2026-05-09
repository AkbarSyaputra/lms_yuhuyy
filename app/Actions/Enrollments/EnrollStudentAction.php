<?php

namespace App\Actions\Enrollments;

use App\Models\Course;
use App\Models\Enrollment;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;

class EnrollStudentAction
{
    public function __construct(
        protected EnrollmentRepositoryInterface $repository
    ) {}

    public function execute(int $userId, Course $course): Enrollment
    {
        // Check if already enrolled
        $existing = $this->repository->checkEnrollment($userId, $course->id);
        if ($existing) {
            return $existing;
        }

        // Optional: Check max_students constraint if your business rule requires it
        if ($course->max_students) {
            $currentEnrollments = $this->repository->getEnrollmentsForCourse($course->id)->count();
            if ($currentEnrollments >= $course->max_students) {
                throw new \Exception('Course has reached its maximum student capacity.');
            }
        }

        return $this->repository->enrollUser($userId, $course->id);
    }
}
