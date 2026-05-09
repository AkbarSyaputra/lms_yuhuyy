<?php

namespace App\Repositories;

use App\Models\Assignment;
use App\Repositories\Contracts\AssignmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AssignmentRepository implements AssignmentRepositoryInterface
{
    public function getForCourse(int $courseId): Collection
    {
        return Assignment::where('course_id', $courseId)
            ->orderBy('due_date')
            ->get();
    }

    public function findById(int $id): ?Assignment
    {
        return Assignment::find($id);
    }

    public function create(array $data): Assignment
    {
        return Assignment::create($data);
    }

    public function update(Assignment $assignment, array $data): Assignment
    {
        $assignment->update($data);

        return $assignment->fresh();
    }

    public function delete(Assignment $assignment): bool
    {
        return $assignment->delete();
    }
}
