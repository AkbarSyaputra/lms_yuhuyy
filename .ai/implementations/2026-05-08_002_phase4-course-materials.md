# [IMPL] Phase 4 - Course Materials & Assignments
- **ID**: IMPL-2026-05-08-002
- **Plan Ref**: PLAN-PHASE-4
- **Tanggal Mulai**: 2026-05-08
- **Tanggal Selesai**: 2026-05-08
- **Author**: AI Agent
- **Status**: done

## Summary
> Implementasi modul Course Materials dan Assignments. Meliputi layer arsitektur backend lengkap (Repository, Action, DTO, Request, Policy, Controller) hingga frontend (Vue/Inertia pages). Memastikan eager loading untuk mencegah N+1 query dan pengamanan akses berbasis course ownership.

## File yang Dibuat / Diubah
| File | Aksi | Keterangan |
|---|---|---|
| `app/Repositories/MaterialRepository.php` | CREATED | Implementasi interface |
| `app/Repositories/AssignmentRepository.php` | CREATED | Implementasi interface |
| `app/Repositories/Contracts/MaterialRepositoryInterface.php` | CREATED | Interface repository |
| `app/Repositories/Contracts/AssignmentRepositoryInterface.php` | CREATED | Interface repository |
| `app/DTOs/MaterialData.php` | CREATED | Spatie Data untuk Material |
| `app/DTOs/AssignmentData.php` | CREATED | Spatie Data untuk Assignment |
| `app/Actions/Materials/...` | CREATED | Action class CRUD Material |
| `app/Actions/Assignments/...` | CREATED | Action class CRUD Assignment |
| `app/Http/Requests/StoreMaterialRequest.php` | CREATED | FormRequest Material |
| `app/Http/Requests/StoreAssignmentRequest.php` | CREATED | FormRequest Assignment |
| `app/Policies/MaterialPolicy.php` | CREATED | Policy Material (Course-level) |
| `app/Policies/AssignmentPolicy.php` | CREATED | Policy Assignment (Course-level) |
| `app/Http/Controllers/MaterialController.php` | CREATED | Controller Materials |
| `app/Http/Controllers/AssignmentController.php` | CREATED | Controller Assignments |
| `resources/js/pages/courses/Show.vue` | CREATED | Tampilan detail course |
| `resources/js/pages/materials/...` | CREATED | Tampilan form Material |
| `resources/js/pages/assignments/...` | CREATED | Tampilan form Assignment |
| `routes/web.php` | MODIFIED | Menambahkan nested resource route |
| `app/Providers/RepositoryServiceProvider.php` | MODIFIED | Bind Material dan Assignment Repository |
| `app/Http/Controllers/CourseController.php` | MODIFIED | Menambahkan eager loading relations |
| `app/Models/Material.php` | MODIFIED | Menambahkan HasFactory |
| `app/Models/Assignment.php` | MODIFIED | Menambahkan HasFactory |
| `database/factories/MaterialFactory.php` | CREATED | Factory Material |
| `database/factories/AssignmentFactory.php` | CREATED | Factory Assignment |

## Perubahan Skema Database
> Tidak ada perubahan migration baru pada sesi ini, model `Material` dan `Assignment` sudah dibuat sejak Phase 1.

## Catatan Implementasi
- Authorization untuk store/create material dan assignment dicek berdasarkan **ownership dari Course** (`$this->authorize('update', $course)`), alih-alih mengecek class Material/Assignment itu sendiri.
- Eager loading ditambahkan pada `CourseController::show` untuk meload materials dan assignments secara efisien tanpa masalah N+1.
- Unit Test mencakup seluruh Action class.
- Feature test mencakup authorization dan operasional CRUD secara keseluruhan pada Controller.
- Menjalankan Pint untuk memastikan standar koding rapi sesuai `fully_qualified_strict_types`.

## Deviasi dari Plan
> Tidak ada.

## Test yang Ditambahkan
- `tests/Unit/Actions/Materials/CreateMaterialActionTest.php`
- `tests/Unit/Actions/Materials/UpdateMaterialActionTest.php`
- `tests/Unit/Actions/Materials/DeleteMaterialActionTest.php`
- `tests/Unit/Actions/Assignments/CreateAssignmentActionTest.php`
- `tests/Unit/Actions/Assignments/UpdateAssignmentActionTest.php`
- `tests/Unit/Actions/Assignments/DeleteAssignmentActionTest.php`
- `tests/Feature/Http/MaterialControllerTest.php`
- `tests/Feature/Http/AssignmentControllerTest.php`

## Review Notes
> Siap untuk di-review. Seluruh tests passing (82 tests).
