# Changelog — 2026-05-08

## Added
- [IMPL-001] Unit tests for `UpdateUserAction` and `DeleteUserAction`.
- [IMPL-001] Method `toDto()` di `StoreUserRequest` dan `UpdateUserRequest`.
- [IMPL-001] Implementation log `2026-05-08_001_phase2-user-management.md`.
- [IMPL-2026-05-08-001] Implementasi manajemen kursus (Course Management)
- [IMPL-2026-05-08-002] Implementasi Course Materials & Assignments lengkap (Repository, Action, Policy, UI Vue)
- [TEST-004] Menambahkan Unit test untuk Materials & Assignments
- [TEST-005] Menambahkan Feature test untuk Material & Assignment Controller
- [IMPL-002] Course & Category Management CRUD (Repositories, DTOs, Actions, Controllers, Policies).
- [IMPL-002] Vue pages for Courses and Categories with premium design.
- [IMPL-002] Feature tests for Course and Category management.

## Modified
- [IMPL-001] `UserData` DTO — tambah default values untuk field opsional.
- [IMPL-001] `UserController` — refactor menggunakan `$request->toDto()`.
- [IMPL-001] `tests/Pest.php` — perbaiki role seeding di `actingAsAdmin` untuk mematikan error validasi di test.
- [IMPL-001] `tests/Feature/Http/UserControllerTest.php` — bersihkan debug code.
- [IMPL-002] `CourseCategory` model — tambah `HasFactory` dan fix imports.
- [IMPL-002] `RepositoryServiceProvider` — bind Category dan Course repositories.

## Fixed
- [FIX-001] Perbaikan test `UserControllerTest` yang sebelumnya gagal karena `exists:roles,name` validation error dan formatting session errors.
- [FIX-002] Perbaikan QueryBuilder `allowedFilters` type error di Repositories.
- [FIX-003] Perbaikan missing `Str` and relation imports di Model `CourseCategory`.

## Notes
> Phase 3: Course & Category Management DONE. Semua feature test (10) hijau.

