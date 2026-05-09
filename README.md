# Eduzy LMS

Eduzy LMS (Learning Management System) is a modern, full-stack educational platform built to facilitate online learning. It provides role-based access for Administrators, Teachers, and Students, offering comprehensive tools for course management, content delivery, and assignment grading.

## Technology Stack

- **Backend:** Laravel 11, PHP 8.3+
- **Frontend:** Vue.js 3 (Composition API), Inertia.js, Tailwind CSS v4
- **Database:** SQLite (default) / MySQL / PostgreSQL
- **Testing:** Pest PHP
- **Pattern:** Action-Repository Architecture with DTOs (Spatie Laravel Data)

## Key Features

### Role-Based Access Control
- **Admin:** Manage users, course categories, and oversee the entire platform.
- **Teacher:** Create and publish courses, upload materials (text/video), create assignments, and grade student submissions.
- **Student:** Browse available courses, enroll, view materials, submit assignments, and track grades.

### Course Management
- Create courses with thumbnails, descriptions, and maximum student capacities.
- Organize courses using categories.
- Manage course status (Draft / Published).

### Learning Materials
- Add structured materials to courses.
- Support for Text-based content and external Video URLs.
- Reorder materials easily via drag-and-drop mechanics (API support).

### Assignments & Grading
- Create assignments with specific due dates and maximum scores.
- Support for multiple submission types (File Upload, Text).
- Secure file storage for student submissions using Laravel's private disk.
- Dedicated dashboard for teachers to review submissions and provide feedback/grades.

### Personalized Dashboards
- Dynamic dashboards tailored to the user's role.
- **Student Stats:** Enrolled courses, pending assignments, recent grades.
- **Teacher Stats:** Total courses taught, enrolled students, pending submissions requiring grading.

## Architecture Guidelines

This project strictly follows these architectural patterns:
1. **Controllers are Thin:** Controllers only receive requests, use Form Requests for validation, dispatch Actions, and return Inertia responses.
2. **Action Classes:** All business logic resides in `app/Actions/` (e.g., `CreateUserAction`, `GradeSubmissionAction`).
3. **Repository Pattern:** All database queries are abstracted into `app/Repositories/` using interfaces. No Eloquent queries inside controllers.
4. **DTOs:** Data transfer between layers utilizes `spatie/laravel-data` for strict typing.
5. **Authorization:** Every resource action is protected by Laravel Policies (e.g., `SubmissionPolicy`).

## Installation & Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd lms_yuhuyy
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install Node Dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Configure your database settings in the `.env` file.*

5. **Run Migrations & Seeders**
   This command will structure the database and populate it with roles, permissions, and demo data.
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Link Storage**
   ```bash
   php artisan storage:link
   ```

7. **Run Development Servers**
   Open two terminal windows:
   ```bash
   # Terminal 1: Run Vite for frontend assets
   npm run dev
   
   # Terminal 2: Run Laravel server
   php artisan serve
   ```

## Default Credentials (DemoDataSeeder)

Running the seeder automatically provisions the following accounts:

| Role | Email | Password |
|---|---|---|
| **Admin** | admin@eduzy.test | `password` |
| **Teacher** | teacher@eduzy.test | `password` |
| **Teacher 2** | teacher2@eduzy.test | `password` |
| **Student** | student@eduzy.test | `password` |
| **Student 2** | student2@eduzy.test | `password` |

## Testing

This project maintains high test coverage using Pest PHP.
```bash
# Run all tests
php artisan test

# Run tests with coverage
php artisan test --coverage
```

## Security

- All file uploads (e.g., assignment submissions) are stored in the `private` disk and can only be downloaded by authorized users via specific endpoints.
- Role validations and authorizations are strictly enforced using `spatie/laravel-permission` and Laravel Policies.
