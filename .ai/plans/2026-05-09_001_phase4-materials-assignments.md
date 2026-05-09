# [PLAN] Phase 4: Course Materials & Assignments
- **ID**: PLAN-2026-05-09-001
- **Tanggal**: 2026-05-09
- **Author**: Antigravity
- **Status**: approved
- **Terkait**: PLAN-2026-05-08-001 (Phase 3)

## Tujuan
> Membangun sistem manajemen materi kursus (Material) dan tugas (Assignment). Pengajar dan Admin dapat menambahkan, mengedit, menghapus, dan mengurutkan materi di dalam kursus mereka. Admin dan Pengajar juga dapat membuat tugas beserta deadline dan nilai maksimum.

## Scope

### In Scope
- [ ] Repository & Interface: `MaterialRepository`, `AssignmentRepository`
- [ ] DTOs: `MaterialData`, `AssignmentData`
- [ ] Form Requests: `StoreMaterialRequest`, `UpdateMaterialRequest`, `StoreAssignmentRequest`, `UpdateAssignmentRequest`
- [ ] Actions Material: `CreateMaterialAction`, `UpdateMaterialAction`, `DeleteMaterialAction`, `ReorderMaterialAction`
- [ ] Actions Assignment: `CreateAssignmentAction`, `UpdateAssignmentAction`, `DeleteAssignmentAction`
- [ ] Policies: `MaterialPolicy`, `AssignmentPolicy`
- [ ] Controllers: `MaterialController`, `AssignmentController` (resource routes nested di bawah course)
- [ ] Register bindings baru di `RepositoryServiceProvider`
- [ ] Inertia Pages: `Courses/Show.vue` (list material & assignment), `Materials/Create.vue`, `Materials/Edit.vue`, `Assignments/Create.vue`, `Assignments/Edit.vue`
- [ ] Unit Tests untuk semua Action baru
- [ ] Feature Tests untuk semua Controller endpoint

### Out of Scope
- File upload untuk Material (akan dijadikan fase lanjutan dengan Spatie Media Library)
- Submission oleh student (Phase 5)
- Enrollment system (Phase 5)
- Penilaian/grading submission (Phase 5)

## Rencana Implementasi

### Langkah-langkah
1. Buat `MaterialRepository` (Interface + Implementasi)
2. Buat `AssignmentRepository` (Interface + Implementasi)
3. Register kedua repository baru di `RepositoryServiceProvider`
4. Buat DTOs: `MaterialData`, `AssignmentData` (gunakan `spatie/laravel-data` dengan `Optional`)
5. Buat Form Requests (dengan `toDto()`)
6. Buat Actions Material (Create, Update, Delete, Reorder)
7. Buat Actions Assignment (Create, Update, Delete)
8. Buat `MaterialPolicy` dan `AssignmentPolicy`
9. Buat `MaterialController` dan `AssignmentController`
10. Tambahkan routes (nested resource di bawah `courses`)
11. Buat Vue Pages (Courses/Show, Materials/Create, Materials/Edit, Assignments/Create, Assignments/Edit)
12. Tulis Unit Tests untuk semua Action
13. Tulis Feature Tests untuk semua Controller

### File yang Akan Dibuat / Diubah
| File | Aksi | Keterangan |
|---|---|---|
| `app/Repositories/Contracts/MaterialRepositoryInterface.php` | CREATE | Interface repository |
| `app/Repositories/MaterialRepository.php` | CREATE | Implementasi |
| `app/Repositories/Contracts/AssignmentRepositoryInterface.php` | CREATE | Interface repository |
| `app/Repositories/AssignmentRepository.php` | CREATE | Implementasi |
| `app/Providers/RepositoryServiceProvider.php` | MODIFY | Bind 2 repo baru |
| `app/DTOs/MaterialData.php` | CREATE | DTO Material |
| `app/DTOs/AssignmentData.php` | CREATE | DTO Assignment |
| `app/Http/Requests/StoreMaterialRequest.php` | CREATE | |
| `app/Http/Requests/UpdateMaterialRequest.php` | CREATE | |
| `app/Http/Requests/StoreAssignmentRequest.php` | CREATE | |
| `app/Http/Requests/UpdateAssignmentRequest.php` | CREATE | |
| `app/Actions/Materials/CreateMaterialAction.php` | CREATE | |
| `app/Actions/Materials/UpdateMaterialAction.php` | CREATE | |
| `app/Actions/Materials/DeleteMaterialAction.php` | CREATE | |
| `app/Actions/Materials/ReorderMaterialAction.php` | CREATE | |
| `app/Actions/Assignments/CreateAssignmentAction.php` | CREATE | |
| `app/Actions/Assignments/UpdateAssignmentAction.php` | CREATE | |
| `app/Actions/Assignments/DeleteAssignmentAction.php` | CREATE | |
| `app/Policies/MaterialPolicy.php` | CREATE | |
| `app/Policies/AssignmentPolicy.php` | CREATE | |
| `app/Http/Controllers/MaterialController.php` | CREATE | |
| `app/Http/Controllers/AssignmentController.php` | CREATE | |
| `routes/web.php` | MODIFY | Nested resource routes |
| `resources/js/pages/courses/Show.vue` | CREATE | Detail kursus + list materi & tugas |
| `resources/js/pages/materials/Create.vue` | CREATE | |
| `resources/js/pages/materials/Edit.vue` | CREATE | |
| `resources/js/pages/assignments/Create.vue` | CREATE | |
| `resources/js/pages/assignments/Edit.vue` | CREATE | |
| `tests/Unit/Actions/Materials/CreateMaterialActionTest.php` | CREATE | |
| `tests/Unit/Actions/Materials/UpdateMaterialActionTest.php` | CREATE | |
| `tests/Unit/Actions/Materials/DeleteMaterialActionTest.php` | CREATE | |
| `tests/Unit/Actions/Assignments/CreateAssignmentActionTest.php` | CREATE | |
| `tests/Unit/Actions/Assignments/UpdateAssignmentActionTest.php` | CREATE | |
| `tests/Unit/Actions/Assignments/DeleteAssignmentActionTest.php` | CREATE | |
| `tests/Feature/Http/MaterialControllerTest.php` | CREATE | |
| `tests/Feature/Http/AssignmentControllerTest.php` | CREATE | |

### Dependency
- `spatie/laravel-data` (sudah terpasang)
- `spatie/laravel-query-builder` (sudah terpasang)
- Models: `Material`, `Assignment` (sudah ada)

## Route Design (Nested Resource)
```
courses/{course}/materials          [index, create, store]
courses/{course}/materials/{material} [edit, update, destroy]
courses/{course}/materials/reorder  [POST] - reorder material

courses/{course}/assignments          [index, create, store]
courses/{course}/assignments/{assignment} [edit, update, destroy]
```

## Risk & Mitigasi
| Risk | Level | Mitigasi |
|---|---|---|
| Teacher mengedit material kursus orang lain | HIGH | Policy `MaterialPolicy` check `course->created_by` |
| Reorder material menyebabkan race condition | MEDIUM | Gunakan DB transaction |
| Order field tidak konsisten saat delete | LOW | Tidak auto-reorder, cukup set null |

## Definition of Done
- [ ] Admin & Teacher bisa CRUD Material di kursus mereka
- [ ] Admin & Teacher bisa CRUD Assignment di kursus mereka
- [ ] Teacher tidak bisa mengedit Material/Assignment di kursus milik orang lain
- [ ] Reorder material berfungsi dengan benar
- [ ] Unit test untuk setiap Action (min. 1 test per action)
- [ ] Feature test untuk setiap endpoint HTTP
- [ ] Tidak ada N+1 query (eager loading `course`, `material`)
- [ ] `php artisan test` — semua PASSED
- [ ] `vendor/bin/pint` — tidak ada isu
- [ ] `npm run types:check` — exit code 0
