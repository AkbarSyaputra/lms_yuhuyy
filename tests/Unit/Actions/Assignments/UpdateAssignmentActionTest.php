<?php

use App\Actions\Assignments\UpdateAssignmentAction;
use App\DTOs\AssignmentData;
use App\Models\Assignment;
use App\Models\Course;
use App\Repositories\AssignmentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can update an existing assignment', function () {
    $repository = new AssignmentRepository;
    $action = new UpdateAssignmentAction($repository);

    $course = Course::factory()->create();
    $assignment = Assignment::factory()->create([
        'course_id' => $course->id,
        'title' => 'Old Assignment',
        'type' => 'text',
    ]);

    $data = AssignmentData::from([
        'title' => 'Updated Assignment',
        'type' => 'quiz',
        'is_published' => true,
        'max_score' => 50,
    ]);

    $updated = $action->execute($assignment, $data);

    expect($updated)->toBeInstanceOf(Assignment::class)
        ->title->toBe('Updated Assignment')
        ->is_published->toBeTrue();

    $this->assertDatabaseHas('assignments', [
        'id' => $assignment->id,
        'title' => 'Updated Assignment',
    ]);
});
