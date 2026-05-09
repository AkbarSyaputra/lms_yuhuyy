<?php

use App\Actions\Submissions\CreateSubmissionAction;
use App\DTOs\SubmissionData;
use App\Enums\SubmissionStatus;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\User;
use App\Repositories\SubmissionRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('private');
    $this->repository = new SubmissionRepository;
    $this->action = new CreateSubmissionAction($this->repository);
});

it('can create a new submission with text content', function () {
    $student = User::factory()->create();
    $assignment = Assignment::factory()->create();
    $data = SubmissionData::from(['content' => 'My answer']);

    $submission = $this->action->execute($student->id, $assignment, $data);

    expect($submission)->toBeInstanceOf(Submission::class)
        ->and($submission->content)->toBe('My answer')
        ->and($submission->student_id)->toBe($student->id)
        ->and($submission->status)->toBe(SubmissionStatus::Submitted);

    $this->assertDatabaseHas('submissions', [
        'student_id' => $student->id,
        'content' => 'My answer',
    ]);
});

it('can create a submission with file upload', function () {
    $student = User::factory()->create();
    $assignment = Assignment::factory()->create();
    $file = UploadedFile::fake()->create('assignment.pdf', 100);
    $data = SubmissionData::from(['file' => $file]);

    $submission = $this->action->execute($student->id, $assignment, $data);

    expect($submission->file_path)->not->toBeNull();
    Storage::disk('private')->assertExists($submission->file_path);
});

it('updates existing submission if student resubmits', function () {
    $student = User::factory()->create();
    $assignment = Assignment::factory()->create();
    $oldSubmission = Submission::factory()->create([
        'student_id' => $student->id,
        'assignment_id' => $assignment->id,
        'content' => 'Old content',
    ]);

    $data = SubmissionData::from(['content' => 'New content']);

    $submission = $this->action->execute($student->id, $assignment, $data);

    expect($submission->id)->toBe($oldSubmission->id)
        ->and($submission->content)->toBe('New content');

    expect(Submission::count())->toBe(1);
});
