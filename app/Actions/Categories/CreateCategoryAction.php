<?php

namespace App\Actions\Categories;

use App\DTOs\CategoryData;
use App\Models\CourseCategory;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class CreateCategoryAction
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {}

    public function execute(CategoryData $data): CourseCategory
    {
        return $this->categoryRepository->create($data->toArray());
    }
}
