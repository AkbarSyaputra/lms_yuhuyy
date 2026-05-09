<?php

namespace App\Actions\Courses;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;

class DeleteCourseAction
{
    public function __construct(
        private readonly CourseRepositoryInterface $courseRepository
    ) {}

    public function execute(Course $course): bool
    {
        return $this->courseRepository->delete($course);
    }
}
