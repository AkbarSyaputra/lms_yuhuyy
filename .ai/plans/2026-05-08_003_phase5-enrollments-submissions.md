# [PLAN] Phase 5 - Student Enrollment & Submissions
- **ID**: PLAN-2026-05-08-003
- **Tanggal**: 2026-05-08
- **Author**: AI Agent
- **Status**: draft
- **Terkait**: Phase 4 Course Materials

## Tujuan
> Mengaktifkan kapabilitas utama bagi siswa untuk bergabung dalam kursus (enrollment) dan berpartisipasi dengan cara mengirimkan tugas (submission), serta memfasilitasi guru untuk memberi nilai.

## Scope
### In Scope
- [ ] Mendaftar (enroll) ke kursus yang published
- [ ] Mencegah double-enrollment dan validasi max_students
- [ ] Mengirimkan tugas berbentuk teks maupun file upload
- [ ] Guru menilai tugas dan memberi feedback
- [ ] Dashboard untuk guru memantau pengumpulan tugas (submission table)
- [ ] Menambahkan trait `HasFactory` pada model `Enrollment` dan `Submission` beserta Factory-nya.
- [ ] UI terintegrasi dengan Vue, Inertia, TailwindCSS.

### Out of Scope
- Sistem pembayaran/checkout (akan ditangani di phase khusus bila diperlukan).
- Integrasi Plagiarism checker.
- Real-time notification (WebSockets) untuk notifikasi penilaian.

## Rencana Implementasi

### Langkah-langkah
1. Siapkan struktur Database (Factory) untuk Enrollment & Submission.
2. Siapkan Repository untuk Enrollment & Submission.
3. Siapkan DTO dan Form Request.
4. Tulis Action Classes (EnrollStudentAction, CreateSubmissionAction, GradeSubmissionAction).
5. Definisikan Policies.
6. Buat Controllers (EnrollmentController, SubmissionController) beserta route nested.
7. Modifikasi dan buat frontend UI (Vue pages).
8. Tulis Unit Tests dan Feature Tests.

### File yang Akan Dibuat / Diubah
| File | Aksi | Keterangan |
|---|---|---|
| `app/Models/Enrollment.php` | MODIFY | Tambah HasFactory |
| `app/Models/Submission.php` | MODIFY | Tambah HasFactory |
| `database/factories/EnrollmentFactory.php` | CREATE | |
| `database/factories/SubmissionFactory.php` | CREATE | |
| `app/Repositories/EnrollmentRepository.php` | CREATE | |
| `app/Repositories/SubmissionRepository.php` | CREATE | |
| `app/DTOs/SubmissionData.php` | CREATE | |
| `app/DTOs/GradeData.php` | CREATE | |
| `app/Actions/Enrollments/EnrollStudentAction.php` | CREATE | |
| `app/Actions/Submissions/CreateSubmissionAction.php` | CREATE | |
| `app/Actions/Submissions/GradeSubmissionAction.php` | CREATE | |
| `app/Policies/EnrollmentPolicy.php` | CREATE | |
| `app/Policies/SubmissionPolicy.php` | CREATE | |
| `app/Http/Controllers/EnrollmentController.php` | CREATE | |
| `app/Http/Controllers/SubmissionController.php` | CREATE | |
| `routes/web.php` | MODIFY | Tambah route |
| `resources/js/pages/courses/Show.vue` | MODIFY | Tambah tombol Enroll |
| `resources/js/pages/assignments/Show.vue` | CREATE | Tampilan tugas & form submit untuk student |
| `resources/js/pages/submissions/Index.vue` | CREATE | Tabel submission untuk guru |
| `resources/js/pages/submissions/Show.vue` | CREATE | Form grading untuk guru |

## Risk & Mitigasi
| Risk | Level | Mitigasi |
|---|---|---|
| File upload dari student berbahaya (XSS/Shell) | HIGH | Validasi mimes strict di Request (`pdf,doc,docx,zip`), simpan di private disk, jangan execute. |
| Kuota pendaftaran bobol saat race condition | MEDIUM | Gunakan validasi/locking atau andalkan constraint unik (user_id, course_id). |

## Definition of Done
- [ ] Factory siap dan di-seed dengan baik.
- [ ] Siswa sukses enroll tanpa error N+1 query.
- [ ] Tombol submit bekerja, file tersimpan di storage lokal.
- [ ] Guru bisa save score & feedback.
- [ ] Unit test mengcover 100% logic di dalam Action.
- [ ] Feature test memastikan access control guru vs murid aman.
- [ ] PR lolos lint (Pint) + test.
