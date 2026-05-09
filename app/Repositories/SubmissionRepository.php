<?php

namespace App\Repositories;

use App\Models\Submission;
use App\Repositories\Contracts\SubmissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SubmissionRepository implements SubmissionRepositoryInterface
{
    public function getSubmissionsForAssignment(int $assignmentId): Collection
    {
        return Submission::with(['student', 'gradedBy'])
            ->where('assignment_id', $assignmentId)
            ->latest('submitted_at')
            ->get();
    }

    public function getSubmissionForUser(int $assignmentId, int $userId): ?Submission
    {
        return Submission::where('assignment_id', $assignmentId)
            ->where('student_id', $userId)
            ->first();
    }

    public function create(array $data): Submission
    {
        return Submission::create($data);
    }

    public function update(Submission $submission, array $data): bool
    {
        return $submission->update($data);
    }
}
