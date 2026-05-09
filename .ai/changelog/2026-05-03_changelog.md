# Changelog — 2026-05-03

## Added
- [IMPL-001] Install spatie/laravel-permission v7.4, spatie/laravel-data v4.22, spatie/laravel-activitylog v5.0, spatie/laravel-query-builder v7.3
- [IMPL-001] Install pestphp/pest v4.6 + pest-plugin-laravel v4.1
- [IMPL-001] Install npm: pinia, vee-validate, zod, @vee-validate/zod
- [IMPL-001] Publish spatie/permission migration + config
- [IMPL-001] Buat 7 migrations baru: users fields, categories, courses, enrollments, materials, assignments, submissions
- [IMPL-001] Buat 7 Enum: UserStatus, CourseStatus, EnrollmentStatus, SubmissionStatus, MaterialType, AssignmentType
- [IMPL-001] Buat 6 Models baru: Course, CourseCategory, Enrollment, Material, Assignment, Submission
- [IMPL-001] Buat RolePermissionSeeder (35 permissions, 3 roles: admin/teacher/student)
- [IMPL-001] Buat DemoDataSeeder (8 users, 5 categories, 3 courses, sample enrollments)

## Modified
- [IMPL-001] `app/Models/User.php` — tambah HasRoles, fillable, enum cast, relasi
- [IMPL-001] `database/seeders/DatabaseSeeder.php` — tambah call ke seeder baru

## Notes
> `php artisan migrate` dan `php artisan db:seed` berhasil berjalan sempurna.
> Phase 1 Foundation DONE. Siap Phase 2: User Management.
