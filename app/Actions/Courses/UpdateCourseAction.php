<?php

namespace App\Actions\Courses;

use App\DTOs\CourseData;
use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;

class UpdateCourseAction
{
    public function __construct(
        private readonly CourseRepositoryInterface $courseRepository
    ) {}

    public function execute(Course $course, CourseData $data): Course
    {
        return $this->courseRepository->update($course, $data->toArray());
    }
}
