# AI Agent Rules — Laravel Fullstack Project
> Stack: Laravel 11 · Vue 3 · Inertia.js · Tailwind CSS v4 · PHP 8.3+ · Redis · MySQL 8 / PostgreSQL

---

## ARSITEKTUR

### ARCH-01 · Layer Separation (WAJIB)
- Controller **hanya boleh**: menerima request, memanggil Action, mengembalikan `Inertia::render()` atau JSON response.
- **Dilarang** menaruh business logic di dalam Controller atau di file View/Page.
- Semua business logic wajib ada di `app/Actions/` atau `app/Services/`.
- Semua query Eloquent wajib ada di `app/Repositories/` — tidak boleh ada `Model::where()` di Controller.

### ARCH-02 · Naming Convention (WAJIB)
| Layer | Format | Contoh |
|---|---|---|
| Action | `{Verb}{Noun}Action.php` | `CreateOrderAction.php` |
| DTO | `{Noun}Data.php` | `UserData.php` |
| Form Request | `{Action}{Model}Request.php` | `StoreOrderRequest.php` |
| Event | `{Noun}{PastVerb}Event.php` | `OrderCreatedEvent.php` |
| Listener | `Handle{EventName}Listener.php` | `HandleOrderCreatedListener.php` |
| Repository | `{Model}Repository.php` | `UserRepository.php` |
| Composable (Vue) | `use{Name}.ts` | `useAuth.ts` |
| Pinia Store | `{domain}Store.ts` | `cartStore.ts` |

### ARCH-03 · Typed DTOs (WAJIB)
- Gunakan `spatie/laravel-data` untuk semua transfer data antar layer.
- **Dilarang** melewatkan array asosiatif mentah (`[]`) antar method/layer.
- DTO harus memiliki validation rules sendiri.
- Controller wajib memanggil `$request->toDto()` sebelum memanggil Action.

```php
// ✅ Benar
public function store(StoreOrderRequest $request, CreateOrderAction $action) {
    $order = $action->execute($request->toDto());
    return Inertia::render('Orders/Show', compact('order'));
}

// ❌ Salah
public function store(Request $request) {
    $order = Order::create($request->all()); // logic di controller + array mentah
    return response()->json($order);
}
```

### ARCH-04 · Struktur Folder
Jangan membuat file di luar struktur berikut tanpa alasan yang jelas:
```
app/
  Actions/       ← satu file = satu use case
  DTOs/
  Enums/
  Events/ + Listeners/
  Exceptions/
  Http/
    Controllers/ ← hanya dispatch action
    Middleware/
    Requests/    ← validation layer
    Resources/   ← API JSON shaping
  Jobs/
  Models/
  Notifications/
  Observers/
  Policies/
  Repositories/
    Contracts/   ← interface
  Services/
  Traits/

resources/js/
  Components/    ← UI atom/molecule
  Composables/   ← prefix "use"
  Layouts/
  Pages/         ← Inertia pages (1:1 route)
  Stores/        ← Pinia
  Types/         ← TypeScript interfaces
  Utils/
```

---

## SECURITY

### SEC-01 · Authorization (WAJIB)
- Setiap resource model **harus punya** Policy di `app/Policies/`.
- Controller wajib memanggil `$this->authorize()` atau `Policy::before()`.
- **Dilarang** menulis `if ($user->role === 'admin')` hardcode — gunakan `$user->can()` atau `Gate::allows()`.
- Role & Permission via `spatie/laravel-permission`, bukan string hardcode.

### SEC-02 · Query Safety (WAJIB)
- **Dilarang** menggunakan string interpolation dalam SQL query.
- Semua filter/search dari user input wajib menggunakan `spatie/query-builder` atau Eloquent binding.
- **Wajib** eager load relasi — tidak boleh ada N+1 query (deteksi via Telescope).
- Gunakan Eloquent ORM untuk semua query — raw SQL hanya jika sangat diperlukan dan wajib menggunakan binding.

### SEC-03 · Input & Data (WAJIB)
- Semua input validasi via **Form Request** — bukan `$request->validate()` di dalam controller.
- Setiap Model wajib mendefinisikan `$fillable` atau `$guarded` (mass assignment protection).
- Field sensitif (password hints, data pribadi) wajib dienkripsi via Model casts.
- File upload: validasi MIME type + ukuran, simpan di private disk bukan `public/`.

### SEC-04 · HTTP Security Headers
Middleware `SecurityHeadersMiddleware` wajib aktif dan menyertakan:
```php
X-Frame-Options: DENY
X-Content-Type-Options: nosniff
Referrer-Policy: strict-origin-when-cross-origin
Content-Security-Policy: default-src 'self'
Permissions-Policy: camera=(), microphone=()
```

### SEC-05 · Environment & Config
- `APP_DEBUG=false` dan `APP_ENV=production` di production — jangan pernah ubah ini.
- **Dilarang** menyimpan credentials dalam kode — selalu gunakan `.env`.
- Session config di production: `secure=true`, `httponly=true`, `samesite=strict`.
- HTTPS only — HSTS header aktif, HTTP harus redirect ke HTTPS.

---

## FRONTEND (Vue 3 / Inertia)

### FE-01 · Vue Component Rules (WAJIB)
- Gunakan **Composition API** dengan `<script setup>` — Options API **dilarang** pada file baru.
- Semua composable wajib prefix `use` (contoh: `useAuth`, `useCart`).
- State global wajib via **Pinia store** — bukan emit chain lebih dari 2 level.
- Props wajib typed dengan `defineProps<{...}>()` atau `withDefaults`.

### FE-02 · Inertia Pattern (WAJIB)
- Gunakan `Inertia::render()` untuk semua halaman — bukan JSON response.
- Data sharing global (user, permissions, flash message) wajib via `HandleInertiaRequests` middleware `share()`.
- **Dilarang** memanggil axios ke route Laravel dari halaman Inertia — gunakan **Inertia form helpers** atau `router`.
- Setiap Inertia Page berada di `resources/js/Pages/` dan 1:1 dengan route.

### FE-03 · TypeScript & Validasi
- Gunakan TypeScript untuk semua file Vue baru — letakkan interface di `resources/js/Types/`.
- Form validation menggunakan `vee-validate` + `zod` (schema-based, type-safe).
- Jangan duplikasi validation rules yang sudah ada di Laravel Form Request.

---

## TESTING

### TEST-01 · Coverage Minimum (WAJIB)
- Setiap `Action` class **wajib** punya **unit test**.
- Setiap endpoint HTTP **wajib** punya **feature test**.
- Coverage minimum **70%** pada direktori `app/Actions/` dan `app/Services/`.

### TEST-02 · Test Isolation (WAJIB)
- Gunakan factory + `fake()` untuk semua test — **dilarang** menyentuh external service.
- Mock/spy semua dependency eksternal (email, storage, 3rd party API).
- Gunakan `RefreshDatabase` atau `DatabaseTransactions` trait agar test tidak saling pengaruhi.
- Testing framework: **Pest PHP** (bukan PHPUnit vanilla).

---

## GIT & WORKFLOW

### GIT-01 · Commit Format (WAJIB)
```
feat: tambah fitur checkout dengan midtrans
fix: perbaiki N+1 query di OrderRepository
refactor: pisahkan OrderService dari controller
test: tambah unit test CreateOrderAction
chore: update dependency spatie/laravel-data
```
Format: `feat|fix|refactor|test|chore|docs|style: deskripsi singkat`

### GIT-02 · PR Rules (WAJIB)
- Satu PR = satu concern (satu fitur / satu bugfix).
- **Dilarang** push langsung ke branch `main` atau `master`.
- PR wajib lolos: **Laravel Pint** (lint), **Pest** (tests), **Larastan level 6** (static analysis).
- PR wajib di-review minimal 1 orang sebelum merge.

---

## PERFORMANCE

### PERF-01 · Caching (WAJIB)
- Cache response yang mahal (config, permission list, menu) via **Redis** dengan TTL eksplisit.
- Jangan cache tanpa TTL — semua `Cache::put()` wajib ada parameter expiry.
- Gunakan **tagged cache** untuk invalidasi yang mudah.

### PERF-02 · Queue (WAJIB)
- **Dilarang** menjalankan operasi blocking di request cycle: email, notifikasi, PDF generation, webhook.
- Semua operasi tersebut wajib masuk **Queue/Job**.
- Gunakan **Laravel Horizon** untuk monitoring antrian Redis.
- Failed jobs wajib dikonfigurasi retry dengan exponential backoff.

### PERF-03 · Database
- Index wajib ada pada semua foreign key dan kolom yang sering difilter/di-sort.
- Gunakan `chunk()` atau `cursor()` untuk proses data masif — jangan `get()` seluruh tabel.
- Aktifkan **Telescope** di development untuk mendeteksi query lambat dan N+1.

---

## SCALABILITY

### SCALE-01 · Stateless Design
- Session, cache, dan queue wajib menggunakan **Redis** (bukan file/database) agar server bisa di-scale horizontal.
- Action dan Service ditulis **stateless** — tidak menyimpan state di property instance.

### SCALE-02 · Event-Driven
- Tambah behavior baru via **Listener baru** — jangan ubah kode Action/Service yang sudah ada (Open/Closed Principle).
- Event nama format past-tense: `OrderCreated`, `UserRegistered`, `PaymentFailed`.

### SCALE-03 · Modularitas
- Kode yang tumbuh besar bisa dipindah ke domain folder atau package terpisah tanpa breaking change.
- Fitur baru wajib bisa di-toggle via **feature flag** di konfigurasi — tanpa deploy ulang.

---

## OBSERVABILITY

### OBS-01 · Structured Logging (WAJIB)
- Semua log wajib dalam format **JSON structured**.
- Setiap log harus mengandung field: `request_id`, `user_id` (nullable), `action`, `status`.
- Gunakan channel terpisah: `app`, `error`, `security`.
- **Dilarang** menggunakan `dd()` atau debug manual di production.

### OBS-02 · Error Monitoring (WAJIB)
- Semua error production wajib dikirim ke monitoring service (Sentry / Bugsnag).
- **Dilarang** menyimpan error hanya di file log tanpa alert system.
- Error kritikal harus trigger notifikasi (email / Slack).

### OBS-03 · Metrics & Alerting
- Wajib memonitor: response time, database query time, queue latency.
- Alert wajib aktif jika:
  - Response time > 2 detik
  - Error rate meningkat drastis
  - Queue delay > threshold

---

## AI AGENT BEHAVIOR

### AI-01 · Intent Processing (WAJIB)
- Semua request AI harus melalui pipeline: **Intent → Validation → Authorization → Action Execution**.
- **Dilarang** langsung mengeksekusi perintah tanpa validasi.

### AI-02 · Context Awareness (WAJIB)
- AI harus mempertimbangkan: role user, state aplikasi, histori interaksi (context/session).

### AI-03 · Safe Execution (WAJIB)
- Semua action AI wajib melewati Policy/Gate dan menggunakan DTO + validation.
- **Dilarang** bypass security layer dengan alasan apapun.

### AI-04 · Fallback Handling
- Jika AI gagal: kembalikan response aman (generic message) atau fallback ke default behavior.
- **Dilarang** expose error internal ke user.

### AI-05 · Idempotency (WAJIB)
- Semua action dari AI harus **idempotent**.
- Gunakan unique request ID atau idempotency key.
- Hindari duplicate execution (contoh: double order).

---

## SECURITY (LANJUTAN)

### SEC-06 · Rate Limiting (WAJIB)
- Terapkan rate limit pada: login endpoint, API endpoint, AI request.
- Default: **60 request / minute / user**.

### SEC-07 · Audit Logging (WAJIB)
- Semua aksi penting wajib dicatat: login/logout, create/update/delete data.
- Gunakan audit trail via `spatie/laravel-activitylog`.

### SEC-08 · Abuse Protection
- Deteksi brute force dan spam request.
- Blokir otomatis via middleware jika threshold terlampaui.

---

## PERFORMANCE (LANJUTAN)

### PERF-04 · HTTP Optimization
- Aktifkan gzip / brotli compression.
- Aktifkan HTTP/2 atau HTTP/3.
- Gunakan CDN untuk semua asset statis.

### PERF-05 · Lazy Loading (WAJIB)
- Component frontend berat wajib **lazy load**.
- Gunakan dynamic import di Vue:
```js
// ✅ Benar
const HeavyChart = defineAsyncComponent(() => import('@/Components/HeavyChart.vue'))
```

### PERF-06 · Database Scaling
- Jika user > 1.000: gunakan **read replica** untuk SELECT, primary DB untuk WRITE.
- Query berat wajib di-cache di Redis.

---

## SCALABILITY (LANJUTAN)

### SCALE-04 · Stateless System (WAJIB)
- **Dilarang** menyimpan state di file lokal.
- Gunakan Redis untuk session, cache, queue — dan object storage (S3) untuk file.

### SCALE-05 · Queue Priority
- Pisahkan queue berdasarkan prioritas:
  - `high` → auth, payment
  - `medium` → notification
  - `low` → email, report
- Worker harus **dedicated per queue**.

### SCALE-06 · Circuit Breaker
- Jika external service gagal: hentikan request sementara, aktifkan fallback response.
- **Dilarang** retry tanpa batas — gunakan exponential backoff + max attempt.

---

## API DESIGN

### API-01 · Versioning (WAJIB)
- Semua API wajib menggunakan versioning: `/api/v1/`, `/api/v2/`.
- Breaking change harus membuat versi baru — jangan ubah endpoint existing.

### API-02 · Response Standard (WAJIB)
- Semua API response harus konsisten menggunakan format berikut:
```json
{
  "success": true,
  "data": {},
  "message": ""
}
```

---

## DEVOPS & DEPLOYMENT

### DEV-01 · CI/CD Pipeline (WAJIB)
Pipeline minimal yang wajib berjalan sebelum deploy:
1. `lint` — Laravel Pint
2. `test` — Pest
3. `static analysis` — Larastan level 6
4. `build & deploy`

### DEV-02 · Zero Downtime Deploy
- Deployment **tidak boleh** menyebabkan downtime.
- Gunakan rolling deploy atau blue-green deployment.

### DEV-03 · Backup Strategy (WAJIB)
- Database backup harian (wajib bisa di-restore).
- File storage backup berkala.
- Redis snapshot (opsional, tergantung kebutuhan).

---

## CONDITIONAL RULES (ADAPTIVE SYSTEM)

### COND-01 · High Traffic Handling
```
IF traffic tinggi
THEN aktifkan caching + queue untuk semua proses non-critical
```

### COND-02 · Slow Query Handling
```
IF query > 500ms
THEN:
  - tambah index
  - cache hasil query
  - evaluasi query plan (EXPLAIN ANALYZE)
```

### COND-03 · Heavy AI Processing
```
IF AI processing > 1 detik
THEN:
  - jalankan via queue
  - gunakan polling atau WebSocket untuk hasil
```

### COND-04 · Role-Based Restriction
```
IF user bukan admin
THEN:
  - batasi akses data sensitif
  - batasi fitur berat
```

### COND-05 · External API Failure
```
IF API gagal > 3x
THEN:
  - aktifkan circuit breaker
  - gunakan fallback response
```

### COND-06 · Large Dataset
```
IF data > 1000 rows
THEN:
  - gunakan pagination
  - gunakan cursor() / streaming
```

### COND-07 · Exception Handling
```
IF terjadi error
THEN:
  - log error (structured JSON)
  - kirim ke monitoring (Sentry/Bugsnag)
  - tampilkan pesan generic ke user (jangan expose detail)
```

---

## FUTURE ENHANCEMENTS (OPSIONAL)

### FUTURE-01 · Realtime System
- Gunakan WebSocket via Laravel Echo / Pusher.
- Hindari polling jika WebSocket tersedia.

### FUTURE-02 · Progressive Web App
- Support offline mode dengan service worker.
- Cache frontend assets via service worker untuk performa lebih baik.

### FUTURE-03 · UX Modern
- Implementasi skeleton loading pada semua halaman yang fetch data.
- Dark mode support via Tailwind `dark:` prefix.
- Smooth transition antar halaman (Inertia progress indicator).

---

## LIBRARIES YANG DISETUJUI

### Backend
| Package | Kegunaan |
|---|---|
| `laravel/breeze` | Auth (session-based, CSRF-safe) |
| `laravel/sanctum` | API token auth |
| `spatie/laravel-permission` | Role & Permission |
| `spatie/laravel-activitylog` | Audit trail |
| `spatie/laravel-data` | Typed DTOs |
| `spatie/laravel-query-builder` | Filter/sort aman dari query string |
| `spatie/laravel-media-library` | File upload & konversi |
| `inertiajs/inertia-laravel` | Bridge Laravel–Vue |
| `laravel/horizon` | Queue monitoring |
| `barryvdh/laravel-telescope` | Debug tool (dev only) |
| `pestphp/pest` | Testing |

### Frontend
| Package | Kegunaan |
|---|---|
| `pinia` | Global state management |
| `vee-validate` + `zod` | Form validation type-safe |
| `@vueuse/core` | Utility composables |
| `@tanstack/vue-query` | Server state & caching |
| `@headlessui/vue` | Accessible UI components |

> ⚠️ Dilarang menambahkan library baru tanpa diskusi dan review terlebih dahulu.

---

## QUICK REFERENCE — CHECKLIST SEBELUM COMMIT

**Arsitektur & Kode**
- [ ] Controller tidak mengandung business logic
- [ ] Semua input divalidasi via Form Request
- [ ] Semua query melalui Repository
- [ ] DTO digunakan antar layer (bukan array mentah)
- [ ] Authorization via Policy / Gate
- [ ] Tidak ada N+1 query
- [ ] Operasi async masuk Queue dengan prioritas yang tepat
- [ ] Unit test ada untuk setiap Action baru
- [ ] Tidak ada credentials di kode
- [ ] Vue menggunakan `<script setup>` dan typed props

**Security & Observability**
- [ ] Rate limiting aktif pada endpoint baru
- [ ] Aksi penting dicatat via activitylog
- [ ] Log menggunakan format JSON structured
- [ ] Error dikirim ke monitoring service (Sentry/Bugsnag)
- [ ] Tidak ada `dd()` atau debug manual tertinggal

**API & DevOps**
- [ ] API response menggunakan format standar `{ success, data, message }`
- [ ] API endpoint menggunakan versioning `/api/v1/`
- [ ] CI/CD pipeline lolos semua tahap (lint, test, static analysis)
- [ ] Component berat menggunakan lazy load / dynamic import