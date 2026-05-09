<?php

use App\Actions\Courses\UpdateCourseAction;
use App\DTOs\CourseData;
use App\Enums\CourseStatus;
use App\Models\Course;
use App\Repositories\CourseRepository;

it('can update an existing course', function () {
    $repository = new CourseRepository;
    $action = new UpdateCourseAction($repository);

    $course = Course::factory()->create([
        'title' => 'Old Title',
        'status' => CourseStatus::Draft,
    ]);

    $data = CourseData::from([
        'title' => 'New Title',
        'status' => CourseStatus::Published,
        'description' => 'Updated desc',
    ]);

    $updatedCourse = $action->execute($course, $data);

    expect($updatedCourse)->toBeInstanceOf(Course::class)
        ->title->toBe('New Title')
        ->status->toBe(CourseStatus::Published);

    $this->assertDatabaseHas('courses', [
        'id' => $course->id,
        'title' => 'New Title',
        'status' => CourseStatus::Published->value,
    ]);
});
