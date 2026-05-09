<?php

namespace App\Repositories\Contracts;

use App\Models\CourseCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator;

    public function getAll(bool $hierarchical = false): Collection;

    public function findById(int $id): ?CourseCategory;

    public function findBySlug(string $slug): ?CourseCategory;

    public function create(array $data): CourseCategory;

    public function update(CourseCategory $category, array $data): CourseCategory;

    public function delete(CourseCategory $category): bool;
}
