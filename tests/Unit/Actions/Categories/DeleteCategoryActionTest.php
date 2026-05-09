<?php

use App\Actions\Categories\DeleteCategoryAction;
use App\Models\CourseCategory;
use App\Repositories\CategoryRepository;

it('can delete a category', function () {
    $repository = new CategoryRepository;
    $action = new DeleteCategoryAction($repository);

    $category = CourseCategory::factory()->create();

    $result = $action->execute($category);

    expect($result)->toBeTrue();
    $this->assertDatabaseMissing('course_categories', ['id' => $category->id]);
});
