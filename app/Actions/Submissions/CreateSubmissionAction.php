<?php

namespace App\Actions\Submissions;

use App\DTOs\SubmissionData;
use App\Models\Assignment;
use App\Models\Submission;
use App\Repositories\Contracts\SubmissionRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class CreateSubmissionAction
{
    public function __construct(
        protected SubmissionRepositoryInterface $repository
    ) {}

    public function execute(int $studentId, Assignment $assignment, SubmissionData $data): Submission
    {
        // Handle file upload if any
        $filePath = null;
        if ($data->file) {
            $filePath = $data->file->store('submissions', 'private');
        }

        // Check if there is an existing submission for this assignment by the student
        $existing = $this->repository->getSubmissionForUser($assignment->id, $studentId);

        $payload = [
            'assignment_id' => $assignment->id,
            'student_id' => $studentId,
            'content' => $data->content,
            'status' => $data->status->value,
            'submitted_at' => now(),
        ];

        if ($filePath) {
            $payload['file_path'] = $filePath;
        }

        if ($existing) {
            // Delete old file if new one is uploaded
            if ($filePath && $existing->file_path) {
                Storage::disk('private')->delete($existing->file_path);
            }
            $this->repository->update($existing, $payload);

            return $existing->refresh();
        }

        return $this->repository->create($payload);
    }
}
