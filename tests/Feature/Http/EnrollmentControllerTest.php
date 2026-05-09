<?php

use App\Enums\CourseStatus;
use App\Models\Course;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);

    $this->student = User::factory()->create();
    $this->student->assignRole('student');

    $this->teacher = User::factory()->create();
    $this->teacher->assignRole('teacher');

    $this->course = Course::factory()->create([
        'status' => CourseStatus::Published,
        'created_by' => $this->teacher->id,
    ]);
});

it('allows student to enroll in a published course', function () {
    $response = $this->actingAs($this->student)
        ->post(route('courses.enroll', $this->course));

    $response->assertRedirect();
    $this->assertDatabaseHas('enrollments', [
        'user_id' => $this->student->id,
        'course_id' => $this->course->id,
    ]);
});

it('forbids teacher from enrolling in a course', function () {
    $response = $this->actingAs($this->teacher)
        ->post(route('courses.enroll', $this->course));

    $response->assertForbidden();
});

it('forbids enrolling in a draft course', function () {
    $draftCourse = Course::factory()->create(['status' => CourseStatus::Draft]);

    $response = $this->actingAs($this->student)
        ->post(route('courses.enroll', $draftCourse));

    $response->assertForbidden();
});
