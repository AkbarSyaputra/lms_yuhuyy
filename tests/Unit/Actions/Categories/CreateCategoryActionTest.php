<?php

use App\Actions\Categories\CreateCategoryAction;
use App\DTOs\CategoryData;
use App\Models\CourseCategory;
use App\Repositories\CategoryRepository;

it('can create a new category', function () {
    $repository = new CategoryRepository;
    $action = new CreateCategoryAction($repository);

    $data = CategoryData::from([
        'name' => 'Web Programming',
        'description' => 'Learn to code web apps',
    ]);

    $category = $action->execute($data);

    expect($category)->toBeInstanceOf(CourseCategory::class)
        ->name->toBe('Web Programming')
        ->slug->toBe('web-programming')
        ->description->toBe('Learn to code web apps');

    $this->assertDatabaseHas('course_categories', [
        'name' => 'Web Programming',
    ]);
});
