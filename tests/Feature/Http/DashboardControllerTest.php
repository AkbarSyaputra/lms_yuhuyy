<?php

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Submission;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
    $this->seed(RolePermissionSeeder::class);
});

it('allows admin to view generic dashboard', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->get(route('dashboard'));

    $response->assertOk();
    // In inertia we can assert the component name
    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Dashboard')
    );
});

it('loads student stats for student', function () {
    $student = User::factory()->create();
    $student->assignRole('student');

    // Create a course, enroll student, create assignment
    $teacher = User::factory()->create();
    $teacher->assignRole('teacher');

    $course = Course::factory()->create(['created_by' => $teacher->id]);
    Enrollment::factory()->create([
        'user_id' => $student->id,
        'course_id' => $course->id,
    ]);
    Assignment::factory()->create([
        'course_id' => $course->id,
    ]);

    $response = $this->actingAs($student)->get(route('dashboard'));

    $response->assertOk();
    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Dashboard')
        ->has('studentStats')
        ->where('studentStats.enrolled_courses_count', 1)
        ->where('studentStats.pending_assignments_count', 1)
    );
});

it('loads teacher stats for teacher', function () {
    $teacher = User::factory()->create();
    $teacher->assignRole('teacher');

    $student = User::factory()->create();
    $student->assignRole('student');

    $course = Course::factory()->create(['created_by' => $teacher->id]);
    Enrollment::factory()->create([
        'user_id' => $student->id,
        'course_id' => $course->id,
    ]);
    $assignment = Assignment::factory()->create([
        'course_id' => $course->id,
    ]);
    Submission::factory()->create([
        'student_id' => $student->id,
        'assignment_id' => $assignment->id,
        'score' => null, // Pending grading
    ]);

    $response = $this->actingAs($teacher)->get(route('dashboard'));

    $response->assertOk();
    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Dashboard')
        ->has('teacherStats')
        ->where('teacherStats.total_courses_count', 1)
        ->where('teacherStats.total_students_count', 1)
        ->where('teacherStats.pending_grading_count', 1)
    );
});
