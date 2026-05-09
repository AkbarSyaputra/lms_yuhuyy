---
trigger: always_on
---

# AI Agent — Planning & History Rules
> Setiap planning dan implementasi yang dikerjakan AI agent **wajib** disimpan ke direktori `.ai/`
> agar semua perubahan memiliki histori yang jelas dan dapat di-review.

---

## Struktur Direktori

```
.ai/
  plans/            ← rencana sebelum implementasi dimulai
  implementations/  ← catatan setelah implementasi selesai
  reviews/          ← hasil review / evaluasi
  changelog/        ← ringkasan perubahan harian
  templates/        ← template standar (jangan diedit sembarangan)
```

> Direktori `.ai/` **wajib di-commit** ke repository. Jangan masukkan ke `.gitignore`.

---

## Format Nama File

| Direktori | Format | Contoh |
|---|---|---|
| `plans/` | `YYYY-MM-DD_NNN_slug.md` | `2025-01-15_001_create-order-feature.md` |
| `implementations/` | `YYYY-MM-DD_NNN_slug.md` | `2025-01-15_001_create-order-feature.md` |
| `reviews/` | `YYYY-MM-DD_review-slug.md` | `2025-01-15_review-sprint-3.md` |
| `changelog/` | `YYYY-MM-DD_changelog.md` | `2025-01-15_changelog.md` |

> `NNN` adalah nomor urut 3 digit auto-increment per hari: `001`, `002`, `003`, dst.

---

## Alur Kerja Wajib

```
1. BUAT PLAN
   └─ Tulis .ai/plans/YYYY-MM-DD_NNN_slug.md
   └─ Status awal: draft

2. KONFIRMASI (jika task berdampak besar)
   └─ Tunggu approval developer sebelum lanjut
   └─ Update status → approved

3. IMPLEMENTASI
   └─ Kerjakan sesuai plan
   └─ Tulis .ai/implementations/YYYY-MM-DD_NNN_slug.md
   └─ Catat setiap file yang dibuat/diubah secara real-time

4. UPDATE STATUS
   └─ Plan → done
   └─ Implementation → done

5. UPDATE CHANGELOG
   └─ Tambah entri di .ai/changelog/YYYY-MM-DD_changelog.md

6. SIAP REVIEW
   └─ Developer review .ai/implementations/ sebelum PR di-merge
```

---

## Template Plan

Simpan di `.ai/templates/plan.md` — gunakan setiap kali memulai task baru.

```markdown
# [PLAN] Nama Fitur / Task
- **ID**: PLAN-YYYY-MM-DD-NNN
- **Tanggal**: YYYY-MM-DD
- **Author**: AI Agent / Nama Developer
- **Status**: draft | approved | in-progress | done | cancelled
- **Terkait**: (link ke plan/impl lain jika ada)

## Tujuan
> Jelaskan MENGAPA task ini dikerjakan. Problem apa yang diselesaikan?

## Scope
### In Scope
- [ ] Item yang akan dikerjakan

### Out of Scope
- Item yang TIDAK dikerjakan dalam task ini

## Rencana Implementasi

### Langkah-langkah
1. Step pertama
2. Step kedua

### File yang Akan Dibuat / Diubah
| File | Aksi | Keterangan |
|---|---|---|
| `app/Actions/Orders/CreateOrderAction.php` | CREATE | Action utama |
| `app/Http/Controllers/OrderController.php` | MODIFY | Tambah method store |

### Dependency
- Package baru yang dibutuhkan (jika ada)
- Migration baru (jika ada)

## Risk & Mitigasi
| Risk | Level | Mitigasi |
|---|---|---|
| Breaking change pada API | HIGH | Buat versi baru /api/v2 |

## Definition of Done
- [ ] Semua file sesuai struktur folder
- [ ] Unit test untuk setiap Action baru
- [ ] Feature test untuk setiap endpoint
- [ ] Tidak ada N+1 query
- [ ] PR lolos lint + test + static analysis
```

---

## Template Implementation Log

Simpan di `.ai/templates/implementation.md` — isi setelah implementasi selesai.

```markdown
# [IMPL] Nama Fitur / Task
- **ID**: IMPL-YYYY-MM-DD-NNN
- **Plan Ref**: PLAN-YYYY-MM-DD-NNN
- **Tanggal Mulai**: YYYY-MM-DD
- **Tanggal Selesai**: YYYY-MM-DD
- **Author**: AI Agent / Nama Developer
- **Status**: in-progress | done | blocked

## Summary
> Ringkasan singkat apa yang sudah dikerjakan.

## File yang Dibuat / Diubah
| File | Aksi | Keterangan |
|---|---|---|
| `app/Actions/Orders/CreateOrderAction.php` | CREATED | |
| `app/Http/Controllers/OrderController.php` | MODIFIED | Tambah method store |
| `tests/Unit/Actions/CreateOrderActionTest.php` | CREATED | |

## Perubahan Skema Database
> Kosongkan jika tidak ada perubahan migration.
```sql
-- Migration: 2025_01_15_000001_create_orders_table.php
CREATE TABLE orders ( ... );
```

## Catatan Implementasi
> Keputusan teknis, workaround, atau hal penting untuk developer lain.

## Deviasi dari Plan
> Jika ada yang berbeda dari planning awal, jelaskan beserta alasannya.
> Kosongkan jika tidak ada deviasi.

## Test yang Ditambahkan
- `tests/Unit/Actions/CreateOrderActionTest.php`
- `tests/Feature/Http/OrderControllerTest.php`

## Review Notes
> Diisi saat code review — temuan, saran, approval status.
```

---

## Template Changelog Harian

Simpan di `.ai/templates/changelog.md` — update setiap hari ada perubahan.

```markdown
# Changelog — YYYY-MM-DD

## Added
- [IMPL-001] Tambah CreateOrderAction + unit test
- [IMPL-002] Tambah endpoint POST /api/v1/orders

## Modified
- [IMPL-003] Refactor OrderRepository menggunakan interface

## Fixed
- [FIX-001] Perbaiki N+1 query di OrderController

## Notes
> Hal penting lainnya yang perlu dicatat hari ini.
```

---

## Aturan Penting

1. **Wajib buat plan dulu** sebelum mulai implementasi — sekecil apapun tasknya.
2. **Dilarang menghapus atau mengedit** file yang sudah berstatus `done` — buat file baru jika ada revisi.
3. **Sebelum task baru**, AI wajib membaca plan dan implementation terakhir yang relevan sebagai konteks.
4. **File `.ai/` di-commit bersamaan** dengan file kode yang bersangkutan — jangan commit terpisah tanpa alasan.
5. **PR description** wajib menyertakan link ke file plan dan implementation terkait.

---

## Integrasi Git

```bash
# Saat membuat plan baru
git add .ai/plans/2025-01-15_001_create-order-feature.md
git commit -m "plan: create order feature"

# Saat implementasi selesai
git add app/Actions/Orders/CreateOrderAction.php
git add .ai/implementations/2025-01-15_001_create-order-feature.md
git add .ai/changelog/2025-01-15_changelog.md
git commit -m "feat: create order feature — with impl log & changelog"
```