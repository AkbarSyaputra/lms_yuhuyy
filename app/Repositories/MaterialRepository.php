<?php

namespace App\Repositories;

use App\Models\Material;
use App\Repositories\Contracts\MaterialRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MaterialRepository implements MaterialRepositoryInterface
{
    public function getForCourse(int $courseId): Collection
    {
        return Material::where('course_id', $courseId)
            ->orderBy('order')
            ->get();
    }

    public function findById(int $id): ?Material
    {
        return Material::find($id);
    }

    public function create(array $data): Material
    {
        return Material::create($data);
    }

    public function update(Material $material, array $data): Material
    {
        $material->update($data);

        return $material->fresh();
    }

    public function delete(Material $material): bool
    {
        return $material->delete();
    }

    public function reorder(int $courseId, array $orderedIds): void
    {
        DB::transaction(function () use ($courseId, $orderedIds) {
            foreach ($orderedIds as $index => $id) {
                Material::where('id', $id)
                    ->where('course_id', $courseId)
                    ->update(['order' => $index + 1]);
            }
        });
    }
}
