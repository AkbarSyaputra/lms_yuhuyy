<?php

namespace App\Actions\Courses;

use App\DTOs\CourseData;
use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CreateCourseAction
{
    public function __construct(
        private readonly CourseRepositoryInterface $courseRepository
    ) {}

    public function execute(CourseData $data): Course
    {
        $payload = $data->toArray();

        // Ensure created_by is set to current user if not provided
        if (empty($payload['created_by'])) {
            $payload['created_by'] = Auth::id();
        }

        return $this->courseRepository->create($payload);
    }
}
