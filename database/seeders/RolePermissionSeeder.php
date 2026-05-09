<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Daftar semua permission per module.
     */
    private array $permissions = [
        // User management
        'users.view',
        'users.create',
        'users.update',
        'users.delete',

        // Course management
        'courses.view',
        'courses.create',
        'courses.update',
        'courses.delete',
        'courses.publish',

        // Enrollment management
        'enrollments.view',
        'enrollments.create',
        'enrollments.update',
        'enrollments.delete',
        'enrollments.import',
        'enrollments.export',

        // Material management
        'materials.view',
        'materials.create',
        'materials.update',
        'materials.delete',

        // Assignment management
        'assignments.view',
        'assignments.create',
        'assignments.update',
        'assignments.delete',

        // Submission management
        'submissions.view',
        'submissions.submit',
        'submissions.grade',

        // Category management
        'categories.view',
        'categories.create',
        'categories.update',
        'categories.delete',

        // Dashboard
        'dashboard.admin',
        'dashboard.teacher',
        'dashboard.student',

        // Reports
        'reports.view',
        'reports.export',
    ];

    /**
     * Permissions per role.
     */
    private array $rolePermissions = [
        'admin' => [
            // Admin punya semua permission
            '*',
        ],

        'teacher' => [
            'courses.view',
            'courses.create',
            'courses.update',
            'courses.publish',
            'enrollments.view',
            'enrollments.create',
            'enrollments.import',
            'enrollments.export',
            'materials.view',
            'materials.create',
            'materials.update',
            'materials.delete',
            'assignments.view',
            'assignments.create',
            'assignments.update',
            'assignments.delete',
            'submissions.view',
            'submissions.grade',
            'categories.view',
            'dashboard.teacher',
            'reports.view',
        ],

        'student' => [
            'courses.view',
            'enrollments.view',
            'materials.view',
            'assignments.view',
            'submissions.view',
            'submissions.submit',
            'dashboard.student',
        ],
    ];

    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create all permissions
        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $this->command->info('✅ '.count($this->permissions).' permissions created.');

        // Create roles and assign permissions
        $allPermissions = Permission::all();

        foreach ($this->rolePermissions as $roleName => $perms) {
            /** @var Role $role */
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

            if ($perms === ['*']) {
                // Admin gets all permissions
                $role->syncPermissions($allPermissions);
                $this->command->info("✅ Role [{$roleName}] assigned ALL permissions.");
            } else {
                $role->syncPermissions($perms);
                $this->command->info("✅ Role [{$roleName}] assigned ".count($perms).' permissions.');
            }
        }
    }
}
