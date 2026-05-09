<?php

namespace App\Actions\Categories;

use App\DTOs\CategoryData;
use App\Models\CourseCategory;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class UpdateCategoryAction
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {}

    public function execute(CourseCategory $category, CategoryData $data): CourseCategory
    {
        return $this->categoryRepository->update($category, $data->toArray());
    }
}
