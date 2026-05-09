<?php

use App\Actions\Materials\CreateMaterialAction;
use App\DTOs\MaterialData;
use App\Models\Course;
use App\Models\Material;
use App\Repositories\MaterialRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a material for a course', function () {
    $repository = new MaterialRepository;
    $action = new CreateMaterialAction($repository);

    $course = Course::factory()->create();

    $data = MaterialData::from([
        'title' => 'Introduction to Laravel',
        'type' => 'text',
        'content' => 'This is the content.',
        'is_published' => true,
    ]);

    $material = $action->execute($course->id, $data);

    expect($material)->toBeInstanceOf(Material::class)
        ->title->toBe('Introduction to Laravel')
        ->course_id->toBe($course->id)
        ->is_published->toBeTrue();

    $this->assertDatabaseHas('materials', [
        'title' => 'Introduction to Laravel',
        'course_id' => $course->id,
    ]);
});
