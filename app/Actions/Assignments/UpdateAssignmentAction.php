<?php

namespace App\Actions\Assignments;

use App\DTOs\AssignmentData;
use App\Models\Assignment;
use App\Repositories\Contracts\AssignmentRepositoryInterface;
use Spatie\LaravelData\Optional;

class UpdateAssignmentAction
{
    public function __construct(
        private readonly AssignmentRepositoryInterface $assignmentRepository,
    ) {}

    public function execute(Assignment $assignment, AssignmentData $data): Assignment
    {
        $payload = [
            'title' => $data->title,
            'type' => $data->type,
            'is_published' => $data->is_published,
        ];

        if (! ($data->description instanceof Optional)) {
            $payload['description'] = $data->description;
        }

        if (! ($data->max_score instanceof Optional)) {
            $payload['max_score'] = $data->max_score;
        }

        if (! ($data->due_date instanceof Optional)) {
            $payload['due_date'] = $data->due_date;
        }

        return $this->assignmentRepository->update($assignment, $payload);
    }
}
