<?php

use App\Actions\Enrollments\EnrollStudentAction;
use App\Enums\EnrollmentStatus;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Repositories\EnrollmentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->repository = new EnrollmentRepository;
    $this->action = new EnrollStudentAction($this->repository);
});

it('can enroll a student in a course', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();

    $enrollment = $this->action->execute($user->id, $course);

    expect($enrollment)->toBeInstanceOf(Enrollment::class)
        ->and($enrollment->user_id)->toBe($user->id)
        ->and($enrollment->course_id)->toBe($course->id)
        ->and($enrollment->status)->toBe(EnrollmentStatus::Active);

    $this->assertDatabaseHas('enrollments', [
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]);
});

it('returns existing enrollment if student is already enrolled', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $existing = Enrollment::factory()->create([
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]);

    $enrollment = $this->action->execute($user->id, $course);

    expect($enrollment->id)->toBe($existing->id);
    expect(Enrollment::count())->toBe(1);
});

it('throws exception if course capacity is reached', function () {
    $course = Course::factory()->create(['max_students' => 1]);
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    // First enrollment
    $this->action->execute($user1->id, $course);

    // Second enrollment should fail
    expect(fn () => $this->action->execute($user2->id, $course))
        ->toThrow(Exception::class, 'Course has reached its maximum student capacity.');
});
