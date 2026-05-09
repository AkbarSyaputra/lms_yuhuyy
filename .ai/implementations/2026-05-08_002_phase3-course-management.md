# [IMPL] Course & Category Management
- **ID**: IMPL-2026-05-08-002
- **Plan Ref**: PLAN-2026-05-08-001
- **Tanggal Mulai**: 2026-05-08
- **Tanggal Selesai**: 2026-05-08
- **Author**: Antigravity
- **Status**: done

## Summary
Implementasi manajemen Kategori Kursus dan Kursus (CRUD) dengan arsitektur Layer Separation (Action, DTO, Repository). Fitur ini mencakup otorisasi berbasis Role & Permission (Spatie) dan tampilan frontend premium menggunakan Vue 3 + Inertia.

## File yang Dibuat / Diubah
| File | Aksi | Keterangan |
|---|---|---|
| `app/Models/CourseCategory.php` | MODIFIED | Tambah HasFactory & fix imports |
| `app/Repositories/Contracts/CategoryRepositoryInterface.php` | CREATED | |
| `app/Repositories/CategoryRepository.php` | CREATED | |
| `app/Repositories/Contracts/CourseRepositoryInterface.php` | CREATED | |
| `app/Repositories/CourseRepository.php` | CREATED | |
| `app/Providers/RepositoryServiceProvider.php` | MODIFIED | Bind new repositories |
| `app/DTOs/CategoryData.php` | CREATED | |
| `app/DTOs/CourseData.php` | CREATED | |
| `app/Http/Requests/StoreCategoryRequest.php` | CREATED | |
| `app/Http/Requests/UpdateCategoryRequest.php` | CREATED | |
| `app/Http/Requests/StoreCourseRequest.php` | CREATED | |
| `app/Http/Requests/UpdateCourseRequest.php` | CREATED | |
| `app/Actions/Categories/CreateCategoryAction.php` | CREATED | |
| `app/Actions/Categories/UpdateCategoryAction.php` | CREATED | |
| `app/Actions/Categories/DeleteCategoryAction.php` | CREATED | |
| `app/Actions/Courses/CreateCourseAction.php` | CREATED | |
| `app/Actions/Courses/UpdateCourseAction.php` | CREATED | |
| `app/Actions/Courses/DeleteCourseAction.php` | CREATED | |
| `app/Policies/CourseCategoryPolicy.php` | CREATED | |
| `app/Policies/CoursePolicy.php` | CREATED | |
| `app/Http/Controllers/CourseCategoryController.php" | CREATED | |
| `app/Http/Controllers/CourseController.php` | CREATED | |
| `routes/web.php` | MODIFIED | Tambah resource routes |
| `database/factories/CourseCategoryFactory.php` | CREATED | |
| `database/factories/CourseFactory.php` | CREATED | |
| `resources/js/pages/categories/Index.vue` | CREATED | |
| `resources/js/pages/courses/Index.vue` | CREATED | |
| `resources/js/pages/courses/Create.vue` | CREATED | |
| `resources/js/pages/courses/Edit.vue` | CREATED | |
| `resources/js/pages/courses/Show.vue` | CREATED | |

## Perubahan Skema Database
Tidak ada perubahan skema (menggunakan tabel yang sudah ada dari migrasi awal).

## Catatan Implementasi
- Menggunakan `Spatie\QueryBuilder` untuk filter dan sort di Repository.
- Otorisasi diatur sedemikian rupa sehingga Teacher hanya bisa mengelola kursusnya sendiri, sementara Admin memiliki akses penuh.
- Folder Vue dipindahkan ke lowercase (`pages/courses/`) untuk konsistensi dengan struktur starter kit yang ada, meskipun aturan awal menyarankan uppercase.

## Deviasi dari Plan
- Folder pages menggunakan lowercase untuk mengikuti struktur asli proyek.

## Test yang Ditambahkan
- `tests/Feature/Http/CourseCategoryControllerTest.php`
- `tests/Feature/Http/CourseControllerTest.php`
