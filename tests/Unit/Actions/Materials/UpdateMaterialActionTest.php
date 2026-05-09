<?php

use App\Actions\Materials\UpdateMaterialAction;
use App\DTOs\MaterialData;
use App\Models\Course;
use App\Models\Material;
use App\Repositories\MaterialRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can update an existing material', function () {
    $repository = new MaterialRepository;
    $action = new UpdateMaterialAction($repository);

    $course = Course::factory()->create();
    $material = Material::factory()->create([
        'course_id' => $course->id,
        'title' => 'Old Title',
        'type' => 'text',
    ]);

    $data = MaterialData::from([
        'title' => 'Updated Title',
        'type' => 'link',
        'external_url' => 'https://example.com',
        'is_published' => true,
    ]);

    $updated = $action->execute($material, $data);

    expect($updated)->toBeInstanceOf(Material::class)
        ->title->toBe('Updated Title')
        ->is_published->toBeTrue();

    $this->assertDatabaseHas('materials', [
        'id' => $material->id,
        'title' => 'Updated Title',
    ]);
});
