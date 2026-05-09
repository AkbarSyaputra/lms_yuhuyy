<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CourseRepository implements CourseRepositoryInterface
{
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return QueryBuilder::for(Course::class)
            ->allowedFilters(
                'title',
                'status',
                AllowedFilter::exact('category_id'),
                AllowedFilter::exact('created_by'),
            )
            ->allowedSorts('title', 'created_at', 'start_date')
            ->with(['category', 'creator'])
            ->withCount('enrollments')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById(int $id): ?Course
    {
        return Course::with(['category', 'creator', 'materials', 'assignments'])->find($id);
    }

    public function findBySlug(string $slug): ?Course
    {
        return Course::with(['category', 'creator', 'materials', 'assignments'])->where('slug', $slug)->first();
    }

    public function create(array $data): Course
    {
        return Course::create($data);
    }

    public function update(Course $course, array $data): Course
    {
        $course->update($data);

        return $course;
    }

    public function delete(Course $course): bool
    {
        return $course->delete();
    }

    public function restore(int $id): bool
    {
        $course = Course::withTrashed()->find($id);

        return $course ? $course->restore() : false;
    }
}
