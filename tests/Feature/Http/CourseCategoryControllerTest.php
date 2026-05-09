<?php

use App\Models\CourseCategory;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    // Setup roles and permissions
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    $teacherRole = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);

    $permissions = ['categories.view', 'categories.create', 'categories.update', 'categories.delete'];
    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
    }

    $adminRole->syncPermissions($permissions);
    $teacherRole->syncPermissions(['categories.view']);
});

it('allows admin to view categories', function () {
    $admin = actingAsAdmin();

    $response = $this->get(route('categories.index'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Categories/Index')
        ->has('categories')
    );
});

it('allows admin to create category', function () {
    actingAsAdmin();

    $response = $this->post(route('categories.store'), [
        'name' => 'Web Development',
        'description' => 'Courses about web development.',
    ]);

    $response->assertRedirect(route('categories.index'));
    $this->assertDatabaseHas('course_categories', [
        'name' => 'Web Development',
        'slug' => 'web-development',
    ]);
});

it('allows admin to update category', function () {
    actingAsAdmin();
    $category = CourseCategory::create(['name' => 'Design']);

    $response = $this->put(route('categories.update', $category), [
        'name' => 'Graphic Design',
    ]);

    $response->assertRedirect(route('categories.index'));
    $this->assertDatabaseHas('course_categories', [
        'id' => $category->id,
        'name' => 'Graphic Design',
    ]);
});

it('allows admin to delete category', function () {
    actingAsAdmin();
    $category = CourseCategory::create(['name' => 'Old Category']);

    $response = $this->delete(route('categories.destroy', $category));

    $response->assertRedirect(route('categories.index'));
    $this->assertDatabaseMissing('course_categories', ['id' => $category->id]);
});

it('forbids teacher from creating category', function () {
    actingAsTeacher();

    $response = $this->post(route('categories.store'), [
        'name' => 'Unauthorized',
    ]);

    $response->assertForbidden();
});
