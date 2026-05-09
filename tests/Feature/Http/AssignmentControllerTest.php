<?php

use App\Enums\AssignmentType;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

$admin = new User();
$teacher = new User();
$course = new Course();

beforeEach(function () use (&$admin, &$teacher, &$course) {
    $this->seed(RolePermissionSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $teacher = User::factory()->create();
    $teacher->assignRole('teacher');

    $course = Course::factory()->create([
        'created_by' => $teacher->id,
    ]);
});

it('allows teacher to create assignment for their course', function () use (&$teacher, &$course) {
    $response = $this->actingAs($teacher)
        ->post(route('assignments.store', $course), [
            'title' => 'Final Project',
            'type' => AssignmentType::FileUpload->value,
            'description' => 'Submit PDF.',
            'max_score' => 100,
        ]);

    $response->assertRedirect(route('courses.show', $course));

    $this->assertDatabaseHas('assignments', [
        'course_id' => $course->id,
        'title' => 'Final Project',
        'max_score' => 100,
    ]);
});

it('forbids teacher from creating assignment in another course', function () use (&$course) {
    $otherTeacher = User::factory()->create();
    $otherTeacher->assignRole('teacher');

    $response = $this->actingAs($otherTeacher)
        ->post(route('assignments.store', $course), [
            'title' => 'Hacked Assignment',
            'type' => AssignmentType::Text->value,
        ]);

    $response->assertForbidden();
});

it('allows teacher to update their assignment', function () use (&$teacher, &$course) {
    $assignment = Assignment::factory()->create(['course_id' => $course->id]);

    $response = $this->actingAs($teacher)
        ->put(route('assignments.update', [$course, $assignment]), [
            'title' => 'Updated Assignment',
            'type' => AssignmentType::Quiz->value,
            'max_score' => 50,
        ]);

    $response->assertRedirect(route('courses.show', $course));

    $this->assertDatabaseHas('assignments', [
        'id' => $assignment->id,
        'title' => 'Updated Assignment',
        'max_score' => 50,
    ]);
});

it('forbids teacher from updating assignment in another course', function () use (&$course) {
    $otherTeacher = User::factory()->create();
    $otherTeacher->assignRole('teacher');
    $assignment = Assignment::factory()->create(['course_id' => $course->id]);

    $response = $this->actingAs($otherTeacher)
        ->put(route('assignments.update', [$course, $assignment]), [
            'title' => 'Hacked Update',
            'type' => AssignmentType::Text->value,
        ]);

    $response->assertForbidden();
});

it('allows teacher to delete their assignment', function () use (&$teacher, &$course) {
    $assignment = Assignment::factory()->create(['course_id' => $course->id]);

    $response = $this->actingAs($teacher)
        ->delete(route('assignments.destroy', [$course, $assignment]));

    $response->assertRedirect(route('courses.show', $course));
    $this->assertSoftDeleted('assignments', ['id' => $assignment->id]);
});
