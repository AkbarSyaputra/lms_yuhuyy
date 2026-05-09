<?php

use App\Actions\Courses\DeleteCourseAction;
use App\Models\Course;
use App\Repositories\CourseRepository;

it('can delete a course', function () {
    $repository = new CourseRepository;
    $action = new DeleteCourseAction($repository);

    $course = Course::factory()->create();

    $result = $action->execute($course);

    expect($result)->toBeTrue();
    $this->assertSoftDeleted('courses', ['id' => $course->id]);
});
