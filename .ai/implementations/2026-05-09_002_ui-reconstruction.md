# [IMPL] Minimalist UI Reconstruction
- **ID**: IMPL-2026-05-09-002
- **Plan Ref**: N/A (Requested directly via prompt for UI enhancement)
- **Tanggal Mulai**: 2026-05-09
- **Tanggal Selesai**: 2026-05-09
- **Author**: AI Agent
- **Status**: done

## Summary
> Melakukan perombakan total (reconstruction) pada UI Eduzy LMS agar mengadaptasi gaya visual yang elegan, minimalis (Notion-inspired), dan premium. Menghapus gradien lama, menggunakan palet warna zinc-based, dan menyederhanakan layout di seluruh modul (Auth, Dashboard, Users, Courses, Materials, Assignments, Submissions, dan Welcome).

## File yang Dibuat / Diubah
| File | Aksi | Keterangan |
|---|---|---|
| `resources/css/app.css` | MODIFIED | Refactor palet warna zinc & base styles |
| `resources/js/components/AppLogo.vue` | MODIFIED | Minimal logo tanpa gradien |
| `resources/js/components/StatsCard.vue` | MODIFIED | Layout bersih dengan border, tanpa bg shadow lebay |
| `resources/js/components/StatusBadge.vue` | MODIFIED | Ring-based badge style |
| `resources/js/components/DataTable.vue` | MODIFIED | Clean data grid, tight headers |
| `resources/js/pages/Welcome.vue` | MODIFIED | Minimal landing page hero & features |
| `resources/js/layouts/auth/AuthSplitLayout.vue` | CREATED | Split screen modern layout |
| `resources/js/pages/Auth/Login.vue` | MODIFIED | Integrasi AuthSplitLayout |
| `resources/js/pages/Auth/Register.vue` | MODIFIED | Integrasi AuthSplitLayout |
| `resources/js/pages/Dashboard.vue` | MODIFIED | Admin grid clean stats |
| `resources/js/components/Dashboard/StudentStats.vue` | MODIFIED | Minimalist list view |
| `resources/js/components/Dashboard/TeacherStats.vue` | MODIFIED | Minimalist list view |
| `resources/js/pages/Users/*` | MODIFIED | Create, Edit, Show, Index dirapikan jadi card sections |
| `resources/js/pages/Courses/*` | MODIFIED | Clean grid list & form layouts |
| `resources/js/pages/Materials/*` | MODIFIED | Create & Edit minimal form |
| `resources/js/pages/Assignments/*` | MODIFIED | Show & Forms minimalis |
| `resources/js/pages/submissions/*` | MODIFIED | Index DataTable & Show grading layout |

## Catatan Implementasi
- Menstandarkan typography dengan font Inter (sans).
- Warna border menggunakan neutral `border-border` untuk memberikan estetika profesional yang ringan.
- Form inputs diubah menggunakan tinggi `h-9` agar compact.

## Review Notes
Semua form model v-model dan properti Inertia masih berjalan normal, perubahan 100% pada presentasi/View layer.
