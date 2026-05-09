<?php

use App\Actions\Assignments\CreateAssignmentAction;
use App\DTOs\AssignmentData;
use App\Models\Assignment;
use App\Models\Course;
use App\Repositories\AssignmentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create an assignment for a course', function () {
    $repository = new AssignmentRepository;
    $action = new CreateAssignmentAction($repository);

    $course = Course::factory()->create();

    $data = AssignmentData::from([
        'title' => 'Final Project',
        'type' => 'file_upload',
        'description' => 'Submit your final project files.',
        'max_score' => 100,
        'is_published' => false,
    ]);

    $assignment = $action->execute($course->id, $data);

    expect($assignment)->toBeInstanceOf(Assignment::class)
        ->title->toBe('Final Project')
        ->course_id->toBe($course->id)
        ->max_score->toBe(100)
        ->is_published->toBeFalse();

    $this->assertDatabaseHas('assignments', [
        'title' => 'Final Project',
        'course_id' => $course->id,
    ]);
});
