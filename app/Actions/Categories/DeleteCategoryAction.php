<?php

namespace App\Actions\Categories;

use App\Models\CourseCategory;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class DeleteCategoryAction
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {}

    public function execute(CourseCategory $category): bool
    {
        // Maybe add logic to prevent deleting if it has courses or children?
        // For now, let's just delete.
        return $this->categoryRepository->delete($category);
    }
}
