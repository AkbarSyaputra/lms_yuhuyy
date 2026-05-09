# [PLAN] Phase 1: Foundation & Authentication System
- **ID**: PLAN-2026-05-03-001
- **Tanggal**: 2026-05-03
- **Author**: AI Agent
- **Status**: in-progress
- **Terkait**: implementation_plan.md.resolved

## Tujuan
> Membangun pondasi LMS Eduzy: install semua dependencies yang diperlukan, setup role/permission, update User model, buat core migrations, enums, dan seed data awal.

## Scope
### In Scope
- [x] STEP 1.1 — Install backend & frontend dependencies
- [x] STEP 1.2 — Setup Spatie Permission (roles: admin, teacher, student)
- [x] STEP 1.3 — Update User model (HasRoles, fillable, relasi)
- [x] STEP 1.4 — Create core migrations (courses, enrollments, materials, assignments, submissions)
- [x] STEP 1.5 — Create Enums (UserStatus, CourseStatus, EnrollmentStatus, SubmissionStatus, dll)
- [x] STEP 1.6 — Seed data (admin, teacher, student users)

### Out of Scope
- Phase 2+ (User CRUD, Course Management, Learning Flow, dll)

## Rencana Implementasi

### Langkah-langkah
1. Install spatie/laravel-permission, spatie/laravel-data, spatie/laravel-activitylog, spatie/laravel-query-builder, pestphp/pest
2. Publish config spatie permission, jalankan migration
3. Install npm: pinia, vee-validate, zod, @vee-validate/zod
4. Update User model dengan HasRoles + relasi
5. Buat migrations untuk semua core tables
6. Buat semua Enum classes
7. Buat RolePermissionSeeder + DemoDataSeeder
8. Buat Models: Course, CourseCategory, Enrollment, Material, Assignment, Submission
9. Update DatabaseSeeder

### File yang Akan Dibuat / Diubah
| File | Aksi | Keterangan |
|---|---|---|
| `app/Models/User.php` | MODIFY | HasRoles, fillable, relasi |
| `app/Models/Course.php` | CREATE | Course model |
| `app/Models/CourseCategory.php` | CREATE | Category model |
| `app/Models/Enrollment.php` | CREATE | Enrollment model |
| `app/Models/Material.php` | CREATE | Material model |
| `app/Models/Assignment.php` | CREATE | Assignment model |
| `app/Models/Submission.php` | CREATE | Submission model |
| `app/Enums/UserStatus.php` | CREATE | active/inactive/suspended |
| `app/Enums/CourseStatus.php` | CREATE | draft/published/archived |
| `app/Enums/EnrollmentStatus.php` | CREATE | active/completed/dropped |
| `app/Enums/SubmissionStatus.php` | CREATE | submitted/graded/returned |
| `app/Enums/MaterialType.php` | CREATE | text/video/file/link |
| `app/Enums/AssignmentType.php` | CREATE | file_upload/text/quiz |
| `database/migrations/xxxx_add_fields_to_users_table.php` | CREATE | avatar, phone, bio, status |
| `database/migrations/xxxx_create_courses_table.php` | CREATE | |
| `database/migrations/xxxx_create_course_categories_table.php` | CREATE | |
| `database/migrations/xxxx_create_enrollments_table.php` | CREATE | |
| `database/migrations/xxxx_create_materials_table.php` | CREATE | |
| `database/migrations/xxxx_create_assignments_table.php` | CREATE | |
| `database/migrations/xxxx_create_submissions_table.php` | CREATE | |
| `database/seeders/RolePermissionSeeder.php` | CREATE | |
| `database/seeders/DemoDataSeeder.php` | CREATE | |
| `database/seeders/DatabaseSeeder.php` | MODIFY | Include seeders |

## Risk & Mitigasi
| Risk | Level | Mitigasi |
|---|---|---|
| Konflik Laravel 13 + spatie/permission | MEDIUM | Gunakan versi terbaru yang kompatibel |
| SQLite tidak support semua fitur | LOW | Dev pakai SQLite, production MySQL |

## Definition of Done
- [ ] `composer install` sukses
- [ ] `php artisan migrate` sukses
- [ ] `php artisan db:seed` sukses
- [ ] Admin/teacher/student user tersedia
- [ ] Roles terdaftar di DB
