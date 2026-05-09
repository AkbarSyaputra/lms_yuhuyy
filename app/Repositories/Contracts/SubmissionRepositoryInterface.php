<?php

namespace App\Repositories\Contracts;

use App\Models\Submission;
use Illuminate\Database\Eloquent\Collection;

interface SubmissionRepositoryInterface
{
    public function getSubmissionsForAssignment(int $assignmentId): Collection;

    public function getSubmissionForUser(int $assignmentId, int $userId): ?Submission;

    public function create(array $data): Submission;

    public function update(Submission $submission, array $data): bool;
}
