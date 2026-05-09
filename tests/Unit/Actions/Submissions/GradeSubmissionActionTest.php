<?php

use App\Actions\Submissions\GradeSubmissionAction;
use App\DTOs\GradeData;
use App\Enums\SubmissionStatus;
use App\Models\Submission;
use App\Models\User;
use App\Repositories\SubmissionRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->repository = new SubmissionRepository;
    $this->action = new GradeSubmissionAction($this->repository);
});

it('can grade a submission', function () {
    $submission = Submission::factory()->create(['status' => SubmissionStatus::Submitted]);
    $teacher = User::factory()->create();
    $data = GradeData::from(['score' => 85, 'feedback' => 'Good job!']);

    $result = $this->action->execute($submission, $teacher->id, $data);

    expect($result)->toBeTrue();

    $submission->refresh();
    expect($submission->score)->toBe(85)
        ->and($submission->feedback)->toBe('Good job!')
        ->and($submission->status)->toBe(SubmissionStatus::Graded)
        ->and($submission->graded_by)->toBe($teacher->id)
        ->and($submission->graded_at)->not->toBeNull();
});
