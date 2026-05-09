<?php

use App\Enums\MaterialType;
use App\Models\Course;
use App\Models\Material;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * @var User $admin
 * @var User $teacher
 * @var Course $course
 */
beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    $this->teacher = User::factory()->create();
    $this->teacher->assignRole('teacher');

    $this->course = Course::factory()->create([
        'created_by' => $this->teacher->id,
    ]);
});

it('allows teacher to create material for their course', function () {
    $response = $this->actingAs($this->teacher)
        ->post(route('materials.store', $this->course), [
            'title' => 'New Material',
            'type' => MaterialType::Text->value,
            'content' => 'Material content here.',
            'is_published' => true,
        ]);

    $response->assertRedirect(route('courses.show', $this->course));

    $this->assertDatabaseHas('materials', [
        'course_id' => $this->course->id,
        'title' => 'New Material',
    ]);
});

it('forbids teacher from creating material in another course', function () {
    $otherTeacher = User::factory()->create();
    $otherTeacher->assignRole('teacher');

    $response = $this->actingAs($otherTeacher)
        ->post(route('materials.store', $this->course), [
            'title' => 'Hacked Material',
            'type' => MaterialType::Text->value,
        ]);

    $response->assertForbidden();
});

it('allows teacher to update their material', function () {
    $material = Material::factory()->create(['course_id' => $this->course->id]);

    $response = $this->actingAs($this->teacher)
        ->put(route('materials.update', [$this->course, $material]), [
            'title' => 'Updated Material',
            'type' => MaterialType::Video->value,
            'external_url' => 'https://youtube.com',
        ]);

    $response->assertRedirect(route('courses.show', $this->course));

    $this->assertDatabaseHas('materials', [
        'id' => $material->id,
        'title' => 'Updated Material',
        'type' => MaterialType::Video->value,
    ]);
});

it('forbids teacher from updating material in another course', function () {
    $otherTeacher = User::factory()->create();
    $otherTeacher->assignRole('teacher');
    $material = Material::factory()->create(['course_id' => $this->course->id]);

    $response = $this->actingAs($otherTeacher)
        ->put(route('materials.update', [$this->course, $material]), [
            'title' => 'Hacked Update',
            'type' => MaterialType::Text->value,
        ]);

    $response->assertForbidden();
});

it('allows teacher to delete their material', function () {
    $material = Material::factory()->create(['course_id' => $this->course->id]);

    $response = $this->actingAs($this->teacher)
        ->delete(route('materials.destroy', [$this->course, $material]));

    $response->assertRedirect(route('courses.show', $this->course));
    $this->assertSoftDeleted('materials', ['id' => $material->id]);
});
