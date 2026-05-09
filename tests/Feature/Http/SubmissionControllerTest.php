<?php

use App\Enums\AssignmentType;
use App\Enums\CourseStatus;
use App\Enums\SubmissionStatus;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Submission;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
    Storage::fake('private');
    $this->seed(RolePermissionSeeder::class);

    $this->student = User::factory()->create();
    $this->student->assignRole('student');

    $this->teacher = User::factory()->create();
    $this->teacher->assignRole('teacher');

    $this->course = Course::factory()->create([
        'status' => CourseStatus::Published,
        'created_by' => $this->teacher->id,
    ]);

    $this->assignment = Assignment::factory()->create([
        'course_id' => $this->course->id,
        'is_published' => true,
        'type' => AssignmentType::Text,
    ]);
});

it('allows enrolled student to view assignment and submit', function () {
    Enrollment::factory()->create([
        'user_id' => $this->student->id,
        'course_id' => $this->course->id,
    ]);

    $response = $this->actingAs($this->student)
        ->get(route('assignments.show', [$this->course, $this->assignment]));

    $response->assertOk();

    $response = $this->actingAs($this->student)
        ->post(route('submissions.store', [$this->course, $this->assignment]), [
            'content' => 'My answer',
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('submissions', [
        'student_id' => $this->student->id,
        'content' => 'My answer',
    ]);
});

it('forbids non-enrolled student from submitting', function () {
    $response = $this->actingAs($this->student)
        ->post(route('submissions.store', [$this->course, $this->assignment]), [
            'content' => 'Illegal answer',
        ]);

    $response->assertForbidden();
});

it('allows teacher to view submissions and grade', function () {
    $submission = Submission::factory()->create([
        'assignment_id' => $this->assignment->id,
        'student_id' => $this->student->id,
    ]);

    $response = $this->actingAs($this->teacher)
        ->get(route('assignments.submissions.index', [$this->course, $this->assignment]));

    $response->assertOk();

    $response = $this->actingAs($this->teacher)
        ->patch(route('submissions.grade', [$this->course, $this->assignment, $submission]), [
            'score' => 90,
            'feedback' => 'Excellente!',
        ]);

    $response->assertRedirect(route('assignments.submissions.index', [$this->course, $this->assignment]));

    $this->assertDatabaseHas('submissions', [
        'id' => $submission->id,
        'score' => 90,
        'status' => SubmissionStatus::Graded,
    ]);
});

it('forbids student from grading', function () {
    $submission = Submission::factory()->create([
        'assignment_id' => $this->assignment->id,
        'student_id' => $this->student->id,
    ]);

    $response = $this->actingAs($this->student)
        ->patch(route('submissions.grade', [$this->course, $this->assignment, $submission]), [
            'score' => 100,
        ]);

    $response->assertForbidden();
});
