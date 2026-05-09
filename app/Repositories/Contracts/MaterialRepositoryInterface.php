<?php

namespace App\Repositories\Contracts;

use App\Models\Material;
use Illuminate\Database\Eloquent\Collection;

interface MaterialRepositoryInterface
{
    public function getForCourse(int $courseId): Collection;

    public function findById(int $id): ?Material;

    public function create(array $data): Material;

    public function update(Material $material, array $data): Material;

    public function delete(Material $material): bool;

    public function reorder(int $courseId, array $orderedIds): void;
}
