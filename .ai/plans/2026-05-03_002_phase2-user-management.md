# [PLAN] Phase 2: User Management
- **ID**: PLAN-2026-05-03-002
- **Tanggal**: 2026-05-03
- **Author**: AI Agent
- **Status**: done
- **Terkait**: PLAN-2026-05-03-001, IMPL-2026-05-03-001

## Tujuan
> Membangun fitur CRUD User yang lengkap: backend (Controller, Actions, Policy, Repository, DTO, Requests) dan frontend (Vue 3 pages dengan table, search, dan form validation).

## Scope
### In Scope
- [x] UserPolicy — hanya admin boleh CRUD
- [x] UserRepository + Interface
- [x] UserData DTO (spatie/laravel-data)
- [x] StoreUserRequest + UpdateUserRequest (Form Request)
- [x] CreateUserAction, UpdateUserAction, DeleteUserAction
- [x] UserController
- [x] Routes
- [x] Types/user.ts (TypeScript interface)
- [x] Pages/Users/Index.vue (datatable + search)
- [x] Pages/Users/Create.vue (form + vee-validate/zod)
- [x] Pages/Users/Edit.vue (form + vee-validate/zod)
- [x] Pages/Users/Show.vue (detail view)
- [x] Unit test CreateUserAction
- [x] Feature test UserController endpoints

### Out of Scope
- Avatar upload (Phase 4, butuh media library)
- Import/export users (Phase 3)

## Rencana Implementasi

### Langkah-langkah
1. Buat UserPolicy
2. Buat UserRepositoryInterface + UserRepository
3. Buat UserData DTO
4. Buat StoreUserRequest + UpdateUserRequest
5. Buat CreateUserAction, UpdateUserAction, DeleteUserAction
6. Buat UserController
7. Register routes (web.php)
8. Buat TypeScript interface user.ts
9. Buat Vue pages: Index, Create, Edit, Show
10. Buat unit test + feature test

### File yang Akan Dibuat / Diubah
| File | Aksi | Keterangan |
|---|---|---|
| `app/Policies/UserPolicy.php` | CREATE | Admin-only CRUD |
| `app/Repositories/Contracts/UserRepositoryInterface.php` | CREATE | Interface |
| `app/Repositories/UserRepository.php` | CREATE | Implementasi |
| `app/DTOs/UserData.php` | CREATE | spatie/laravel-data DTO |
| `app/Http/Requests/StoreUserRequest.php` | CREATE | Validasi create |
| `app/Http/Requests/UpdateUserRequest.php` | CREATE | Validasi update |
| `app/Actions/Users/CreateUserAction.php` | CREATE | Logic create user |
| `app/Actions/Users/UpdateUserAction.php` | CREATE | Logic update user |
| `app/Actions/Users/DeleteUserAction.php` | CREATE | Logic delete user |
| `app/Http/Controllers/UserController.php` | CREATE | Dispatch ke Actions |
| `routes/web.php` | MODIFY | Tambah user routes |
| `resources/js/Types/user.ts` | CREATE | TypeScript interfaces |
| `resources/js/Pages/Users/Index.vue` | CREATE | List + search |
| `resources/js/Pages/Users/Create.vue` | CREATE | Form create |
| `resources/js/Pages/Users/Edit.vue` | CREATE | Form edit |
| `resources/js/Pages/Users/Show.vue` | CREATE | Detail view |
| `tests/Unit/Actions/CreateUserActionTest.php` | CREATE | Unit test |
| `tests/Feature/Http/UserControllerTest.php` | CREATE | Feature test |

## Definition of Done
- [ ] Admin bisa CRUD user via UI
- [ ] Non-admin mendapat 403 Forbidden
- [ ] Form validation bekerja (frontend + backend)
- [ ] Unit test + feature test pass
