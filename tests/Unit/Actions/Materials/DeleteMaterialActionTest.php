<?php

use App\Actions\Materials\DeleteMaterialAction;
use App\Models\Course;
use App\Models\Material;
use App\Repositories\MaterialRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can soft-delete a material', function () {
    $repository = new MaterialRepository;
    $action = new DeleteMaterialAction($repository);

    $course = Course::factory()->create();
    $material = Material::factory()->create(['course_id' => $course->id]);

    $result = $action->execute($material);

    expect($result)->toBeTrue();

    $this->assertSoftDeleted('materials', ['id' => $material->id]);
});
