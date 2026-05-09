<?php

use App\Actions\Categories\UpdateCategoryAction;
use App\DTOs\CategoryData;
use App\Models\CourseCategory;
use App\Repositories\CategoryRepository;

it('can update an existing category', function () {
    $repository = new CategoryRepository;
    $action = new UpdateCategoryAction($repository);

    $category = CourseCategory::factory()->create([
        'name' => 'Old Name',
        'description' => 'Old desc',
    ]);

    $data = CategoryData::from([
        'name' => 'New Name',
        'description' => 'New desc',
    ]);

    $updatedCategory = $action->execute($category, $data);

    expect($updatedCategory)->toBeInstanceOf(CourseCategory::class)
        ->name->toBe('New Name')
        ->description->toBe('New desc');

    $this->assertDatabaseHas('course_categories', [
        'id' => $category->id,
        'name' => 'New Name',
    ]);
});
