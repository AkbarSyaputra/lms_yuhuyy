# [IMPL] Phase 5: Student Enrollment & Submissions
- **ID**: IMPL-2026-05-09-001
- **Plan Ref**: PLAN-2026-05-08-001
- **Tanggal Mulai**: 2026-05-09
- **Tanggal Selesai**: 2026-05-09
- **Author**: AI Agent
- **Status**: done

## Summary
Implementasi fitur pendaftaran kursus (enrollment) untuk siswa dan sistem pengumpulan tugas (submission) serta penilaian (grading).

## File yang Dibuat / Diubah
| File | Aksi | Keterangan |
|---|---|---|
| `app/Models/Enrollment.php` | MODIFY | Tambah HasFactory |
| `app/Models/Submission.php` | MODIFY | Tambah HasFactory |
| `database/factories/EnrollmentFactory.php` | NEW | |
| `database/factories/SubmissionFactory.php` | NEW | |
| `app/Repositories/EnrollmentRepository.php` | NEW | |
| `app/Repositories/SubmissionRepository.php` | NEW | |
| `app/DTOs/SubmissionData.php` | NEW | |
| `app/DTOs/GradeData.php` | NEW | |
| `app/Actions/Enrollments/EnrollStudentAction.php` | NEW | |
| `app/Actions/Submissions/CreateSubmissionAction.php` | NEW | |
| `app/Actions/Submissions/GradeSubmissionAction.php` | NEW | |
| `app/Policies/EnrollmentPolicy.php` | NEW | |
| `app/Policies/SubmissionPolicy.php` | NEW | |
| `app/Http/Controllers/EnrollmentController.php` | NEW | |
| `app/Http/Controllers/SubmissionController.php` | NEW | |
| `resources/js/Pages/Courses/Show.vue` | MODIFY | Tambah tombol Enroll & logic |
| `resources/js/Pages/Assignments/Show.vue` | NEW | Halaman submit tugas |
| `resources/js/Pages/Submissions/Index.vue` | NEW | Dashboard grading guru |
| `resources/js/Pages/Submissions/Show.vue` | NEW | Form penilaian guru |

## Catatan Implementasi
- Menggunakan disk `private` untuk file submission guna menjaga privasi data siswa.
- Folder pages `assignments`, `materials`, dan `submissions` diubah ke PascalCase untuk konsistensi.
- Menambahkan `withoutVite()` pada test untuk menghindari error Vite manifest saat testing GET routes.

## Test yang Ditambahkan
- `tests/Unit/Actions/Enrollments/EnrollStudentActionTest.php`
- `tests/Unit/Actions/Submissions/CreateSubmissionActionTest.php`
- `tests/Unit/Actions/Submissions/GradeSubmissionActionTest.php`
- `tests/Feature/Http/EnrollmentControllerTest.php`
- `tests/Feature/Http/SubmissionControllerTest.php`
- **Total Test: 96 Passed**
