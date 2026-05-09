<?php

namespace App\Actions\Submissions;

use App\DTOs\GradeData;
use App\Models\Submission;
use App\Repositories\Contracts\SubmissionRepositoryInterface;

class GradeSubmissionAction
{
    public function __construct(
        protected SubmissionRepositoryInterface $repository
    ) {}

    public function execute(Submission $submission, int $teacherId, GradeData $data): bool
    {
        return $this->repository->update($submission, [
            'score' => $data->score,
            'feedback' => $data->feedback,
            'status' => $data->status->value,
            'graded_by' => $teacherId,
            'graded_at' => now(),
        ]);
    }
}
