<?php

use App\Actions\Courses\CreateCourseAction;
use App\DTOs\CourseData;
use App\Enums\CourseStatus;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\User;
use App\Repositories\CourseRepository;
use Illuminate\Support\Facades\Auth;

it('can create a new course and automatically sets created_by', function () {
    $repository = new CourseRepository;
    $action = new CreateCourseAction($repository);

    $user = User::factory()->create();
    Auth::login($user);

    $category = CourseCategory::factory()->create();

    $data = CourseData::from([
        'title' => 'Master Laravel',
        'status' => CourseStatus::Draft,
        'category_id' => $category->id,
        'description' => 'Learn Laravel correctly',
    ]);

    $course = $action->execute($data);

    expect($course)->toBeInstanceOf(Course::class)
        ->title->toBe('Master Laravel')
        ->created_by->toBe($user->id)
        ->status->toBe(CourseStatus::Draft);

    $this->assertDatabaseHas('courses', [
        'title' => 'Master Laravel',
        'created_by' => $user->id,
    ]);
});
