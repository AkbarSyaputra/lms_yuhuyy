<?php

use App\Actions\Assignments\DeleteAssignmentAction;
use App\Models\Assignment;
use App\Models\Course;
use App\Repositories\AssignmentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can soft-delete an assignment', function () {
    $repository = new AssignmentRepository;
    $action = new DeleteAssignmentAction($repository);

    $course = Course::factory()->create();
    $assignment = Assignment::factory()->create(['course_id' => $course->id]);

    $result = $action->execute($assignment);

    expect($result)->toBeTrue();

    $this->assertSoftDeleted('assignments', ['id' => $assignment->id]);
});
