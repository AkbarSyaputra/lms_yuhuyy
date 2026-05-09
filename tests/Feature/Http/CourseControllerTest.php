<?php

use App\Enums\CourseStatus;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    $teacherRole = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);

    $permissions = ['courses.view', 'courses.create', 'courses.update', 'courses.delete', 'categories.view'];
    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
    }

    $adminRole->syncPermissions($permissions);
    $teacherRole->syncPermissions(['courses.view', 'courses.create', 'courses.update', 'courses.delete', 'categories.view']);
});

it('allows admin to view courses', function () {
    actingAsAdmin();

    $response = $this->get(route('courses.index'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Courses/Index')
        ->has('courses')
    );
});

it('allows teacher to create course', function () {
    $teacher = actingAsTeacher();
    $category = CourseCategory::create(['name' => 'IT']);

    $response = $this->post(route('courses.store'), [
        'title' => 'Laravel Advanced',
        'category_id' => $category->id,
        'status' => CourseStatus::Draft->value,
    ]);

    $response->assertRedirect(route('courses.index'));
    $this->assertDatabaseHas('courses', [
        'title' => 'Laravel Advanced',
        'created_by' => $teacher->id,
        'category_id' => $category->id,
    ]);
});

it('allows teacher to update their own course', function () {
    $teacher = actingAsTeacher();
    $course = Course::factory()->create(['created_by' => $teacher->id]);

    $response = $this->put(route('courses.update', $course), [
        'title' => 'Updated Title',
        'status' => CourseStatus::Published->value,
    ]);

    $response->assertRedirect(route('courses.index'));
    $this->assertDatabaseHas('courses', [
        'id' => $course->id,
        'title' => 'Updated Title',
        'status' => CourseStatus::Published->value,
    ]);
});

it('forbids teacher from updating others course', function () {
    actingAsTeacher();
    $otherTeacher = User::factory()->create();
    $course = Course::factory()->create(['created_by' => $otherTeacher->id]);

    $response = $this->put(route('courses.update', $course), [
        'title' => 'Malicious Update',
        'status' => CourseStatus::Published->value,
    ]);

    $response->assertForbidden();
});

it('allows admin to update any course', function () {
    actingAsAdmin();
    $teacher = User::factory()->create();
    $course = Course::factory()->create(['created_by' => $teacher->id]);

    $response = $this->put(route('courses.update', $course), [
        'title' => 'Admin Update',
        'status' => CourseStatus::Published->value,
    ]);

    $response->assertRedirect(route('courses.index'));
});
