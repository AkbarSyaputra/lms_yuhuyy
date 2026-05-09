# [PLAN] Phase 3: Course & Category Management
- **ID**: PLAN-2026-05-08-001
- **Tanggal**: 2026-05-08
- **Author**: Antigravity
- **Status**: draft
- **Terkait**: PLAN-2026-05-03-001, PLAN-2026-05-03-002

## Tujuan
> Membangun sistem manajemen kursus dan kategori. Admin dapat mengelola kategori (hierarkis), sementara Admin dan Pengajar dapat membuat serta mengelola kursus.

## Scope
### In Scope
- [ ] CRUD Course Categories (Admin)
- [ ] CRUD Courses (Admin & Teacher)
- [ ] Repository & Interface untuk Category & Course
- [ ] DTOs: CategoryData, CourseData
- [ ] Actions: CreateCategory, UpdateCategory, DeleteCategory, CreateCourse, UpdateCourse, DeleteCourse
- [ ] Form Requests with `toDto()`
- [ ] Controllers: CourseCategoryController, CourseController
- [ ] Inertia Pages for both
- [ ] Soft delete handling for Courses
- [ ] Unit & Feature tests

### Out of Scope
- File upload (Thumbnail) - akan dihandle di Phase 4 dengan Media Library
- Course Materials (Phase 4)
- Enrollment system (Phase 5)

## Rencana Implementasi

### Langkah-langkah
1. Buat Repository Interface & Implementasi untuk Category & Course.
2. Buat DTOs: `CategoryData` dan `CourseData`.
3. Buat Form Requests: `StoreCategoryRequest`, `UpdateCategoryRequest`, `StoreCourseRequest`, `UpdateCourseRequest`.
4. Buat Actions untuk Category (CRUD).
5. Buat Actions untuk Course (CRUD).
6. Buat Controllers: `CourseCategoryController` dan `CourseController`.
7. Tambahkan Routes.
8. Buat Vue Pages di `resources/js/Pages/Categories/` dan `resources/js/Pages/Courses/`.
9. Tambahkan Unit & Feature Tests.

### File yang Akan Dibuat / Diubah
| File | Aksi | Keterangan |
|---|---|---|
| `app/Repositories/Contracts/CategoryRepositoryInterface.php` | CREATE | |
| `app/Repositories/CategoryRepository.php` | CREATE | |
| `app/Repositories/Contracts/CourseRepositoryInterface.php` | CREATE | |
| `app/Repositories/CourseRepository.php` | CREATE | |
| `app/DTOs/CategoryData.php` | CREATE | |
| `app/DTOs/CourseData.php` | CREATE | |
| `app/Actions/Categories/*.php` | CREATE | Actions CRUD Kategori |
| `app/Actions/Courses/*.php` | CREATE | Actions CRUD Kursus |
| `app/Http/Controllers/CourseCategoryController.php` | CREATE | |
| `app/Http/Controllers/CourseController.php` | CREATE | |
| `app/Http/Requests/*` | CREATE | Form Requests |
| `routes/web.php` | MODIFY | Tambah routes |
| `resources/js/Pages/Categories/*` | CREATE | Vue Pages Kategori |
| `resources/js/Pages/Courses/*` | CREATE | Vue Pages Kursus |
| `tests/Unit/Actions/*` | CREATE | Unit tests |
| `tests/Feature/Http/*` | CREATE | Feature tests |

## Risk & Mitigasi
| Risk | Level | Mitigasi |
|---|---|---|
| Kursus tanpa kategori | LOW | Category ID dibuat nullable (sudah di migration) |
| Teacher mengedit kursus orang lain | HIGH | Gunakan Policy `update` dengan check `created_by` |

## Definition of Done
- [ ] Admin bisa CRUD Kategori.
- [ ] Admin & Teacher bisa CRUD Kursus (Teacher hanya miliknya).
- [ ] N+1 query terhindari dengan eager loading (Category & Creator).
- [ ] Unit test coverage > 70% untuk Actions baru.
- [ ] PR lolos lint + test.
