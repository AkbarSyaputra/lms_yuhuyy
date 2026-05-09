<?php

namespace App\Repositories;

use App\Models\CourseCategory;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return QueryBuilder::for(CourseCategory::class)
            ->allowedFilters('name', 'slug')
            ->allowedSorts('name', 'created_at')
            ->with('parent')
            ->withCount('courses')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getAll(bool $hierarchical = false): Collection
    {
        $query = CourseCategory::query()->orderBy('name');

        if ($hierarchical) {
            $query->whereNull('parent_id')->with('children');
        }

        return $query->get();
    }

    public function findById(int $id): ?CourseCategory
    {
        return CourseCategory::with(['parent', 'children'])->find($id);
    }

    public function findBySlug(string $slug): ?CourseCategory
    {
        return CourseCategory::with(['parent', 'children'])->where('slug', $slug)->first();
    }

    public function create(array $data): CourseCategory
    {
        return CourseCategory::create($data);
    }

    public function update(CourseCategory $category, array $data): CourseCategory
    {
        $category->update($data);

        return $category;
    }

    public function delete(CourseCategory $category): bool
    {
        return $category->delete();
    }
}
