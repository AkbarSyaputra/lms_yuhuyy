<?php

namespace Database\Seeders;

use App\Enums\CourseStatus;
use App\Enums\EnrollmentStatus;
use App\Enums\UserStatus;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\Submission;
use App\Enums\MaterialType;
use App\Enums\AssignmentType;
use App\Enums\SubmissionStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────────
        // 1. Create Users
        // ─────────────────────────────────────────────

        $admin = User::firstOrCreate(
            ['email' => 'admin@eduzy.test'],
            [
                'name' => 'Admin Eduzy',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'bio' => 'System administrator for Eduzy LMS.',
                'status' => UserStatus::Active,
            ]
        );
        $admin->assignRole('admin');
        $this->command->info('✅ Admin user: admin@eduzy.test / password');

        $teacher1 = User::firstOrCreate(
            ['email' => 'teacher@eduzy.test'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'phone' => '082345678901',
                'bio' => 'Experienced teacher in Computer Science and Web Development.',
                'status' => UserStatus::Active,
            ]
        );
        $teacher1->assignRole('teacher');

        $teacher2 = User::firstOrCreate(
            ['email' => 'teacher2@eduzy.test'],
            [
                'name' => 'Sari Dewi',
                'password' => Hash::make('password'),
                'phone' => '083456789012',
                'bio' => 'Mathematics and Data Science instructor.',
                'status' => UserStatus::Active,
            ]
        );
        $teacher2->assignRole('teacher');
        $this->command->info('✅ Teacher users created (teacher@eduzy.test, teacher2@eduzy.test)');

        $students = [];
        $studentData = [
            ['name' => 'Andi Pratama',    'email' => 'student@eduzy.test'],
            ['name' => 'Citra Lestari',   'email' => 'student2@eduzy.test'],
            ['name' => 'Deni Firmansyah', 'email' => 'student3@eduzy.test'],
            ['name' => 'Eka Putri',       'email' => 'student4@eduzy.test'],
            ['name' => 'Fajar Nugroho',   'email' => 'student5@eduzy.test'],
        ];

        foreach ($studentData as $data) {
            $student = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'status' => UserStatus::Active,
                ]
            );
            $student->assignRole('student');
            $students[] = $student;
        }
        $this->command->info('✅ 5 student users created (student@eduzy.test ... student5@eduzy.test)');

        // ─────────────────────────────────────────────
        // 2. Create Course Categories
        // ─────────────────────────────────────────────

        $categories = [
            ['name' => 'Pemrograman',        'slug' => 'pemrograman'],
            ['name' => 'Matematika',          'slug' => 'matematika'],
            ['name' => 'Desain',              'slug' => 'desain'],
            ['name' => 'Bahasa',              'slug' => 'bahasa'],
            ['name' => 'Ilmu Pengetahuan',    'slug' => 'ilmu-pengetahuan'],
        ];

        $createdCategories = [];
        foreach ($categories as $cat) {
            $createdCategories[$cat['slug']] = CourseCategory::firstOrCreate(
                ['slug' => $cat['slug']],
                ['name' => $cat['name']]
            );
        }
        $this->command->info('✅ 5 course categories created.');

        // ─────────────────────────────────────────────
        // 3. Create Sample Courses
        // ─────────────────────────────────────────────

        $course1 = Course::firstOrCreate(
            ['slug' => 'web-development-dasar'],
            [
                'title' => 'Web Development Dasar',
                'description' => 'Belajar HTML, CSS, JavaScript dari nol hingga bisa membuat website dinamis.',
                'category_id' => $createdCategories['pemrograman']->id,
                'created_by' => $teacher1->id,
                'status' => CourseStatus::Published,
                'max_students' => 30,
                'start_date' => now()->toDateString(),
                'end_date' => now()->addMonths(3)->toDateString(),
            ]
        );

        $course2 = Course::firstOrCreate(
            ['slug' => 'laravel-untuk-pemula'],
            [
                'title' => 'Laravel untuk Pemula',
                'description' => 'Membangun aplikasi web modern menggunakan framework Laravel 11.',
                'category_id' => $createdCategories['pemrograman']->id,
                'created_by' => $teacher1->id,
                'status' => CourseStatus::Published,
                'max_students' => 25,
                'start_date' => now()->toDateString(),
                'end_date' => now()->addMonths(4)->toDateString(),
            ]
        );

        $course3 = Course::firstOrCreate(
            ['slug' => 'matematika-diskrit'],
            [
                'title' => 'Matematika Diskrit',
                'description' => 'Dasar-dasar matematika diskrit untuk ilmu komputer: logika, himpunan, relasi, graf.',
                'category_id' => $createdCategories['matematika']->id,
                'created_by' => $teacher2->id,
                'status' => CourseStatus::Draft,
                'max_students' => 40,
            ]
        );

        $this->command->info('✅ 3 sample courses created.');

        // ─────────────────────────────────────────────
        // 4. Create Enrollments
        // ─────────────────────────────────────────────

        $enrollCourses = [$course1, $course2];

        foreach ($students as $index => $student) {
            // All students enroll in course1
            Enrollment::firstOrCreate(
                ['user_id' => $student->id, 'course_id' => $course1->id],
                [
                    'status' => EnrollmentStatus::Active,
                    'progress_percentage' => rand(0, 80),
                    'enrolled_at' => now()->subDays(rand(1, 30)),
                ]
            );

            // First 3 students also enroll in course2
            if ($index < 3) {
                Enrollment::firstOrCreate(
                    ['user_id' => $student->id, 'course_id' => $course2->id],
                    [
                        'status' => EnrollmentStatus::Active,
                        'progress_percentage' => rand(0, 50),
                        'enrolled_at' => now()->subDays(rand(1, 15)),
                    ]
                );
            }
        }

        $this->command->info('✅ Sample enrollments created.');

        // ─────────────────────────────────────────────
        // 5. Create Materials & Assignments
        // ─────────────────────────────────────────────

        $material1 = Material::firstOrCreate(
            ['course_id' => $course1->id, 'title' => 'Pengenalan Web Development'],
            [
                'type' => MaterialType::Text,
                'content' => 'Web development adalah proses pembuatan website atau aplikasi berbasis web. Anda akan belajar HTML, CSS, dan JavaScript dasar.',
                'order' => 1,
                'is_published' => true,
            ]
        );

        $material2 = Material::firstOrCreate(
            ['course_id' => $course1->id, 'title' => 'HTML & CSS Dasar'],
            [
                'type' => MaterialType::Video,
                'external_url' => 'https://www.youtube.com/watch?v=G3e-cpL7ofc',
                'order' => 2,
                'is_published' => true,
            ]
        );

        $assignment1 = Assignment::firstOrCreate(
            ['course_id' => $course1->id, 'title' => 'Tugas 1: Membuat Halaman Profil HTML'],
            [
                'description' => 'Buat halaman profil sederhana menggunakan HTML. Kumpulkan file dalam format zip.',
                'type' => AssignmentType::FileUpload,
                'max_score' => 100,
                'due_date' => now()->addDays(7),
                'is_published' => true,
            ]
        );

        $assignment2 = Assignment::firstOrCreate(
            ['course_id' => $course2->id, 'title' => 'Tugas: Instalasi Laravel'],
            [
                'description' => 'Tuliskan langkah-langkah yang Anda lakukan saat menginstal Laravel.',
                'type' => AssignmentType::Text,
                'max_score' => 100,
                'due_date' => now()->addDays(14),
                'is_published' => true,
            ]
        );

        $this->command->info('✅ Sample materials & assignments created.');

        // ─────────────────────────────────────────────
        // 6. Create Submissions
        // ─────────────────────────────────────────────

        // $students[0] (Andi) submits and gets graded
        Submission::firstOrCreate(
            ['assignment_id' => $assignment1->id, 'student_id' => $students[0]->id],
            [
                'content' => null,
                'file_path' => 'submissions/dummy.zip',
                'score' => 95,
                'feedback' => 'Sangat bagus! Struktur HTML rapi.',
                'status' => SubmissionStatus::Graded,
                'submitted_at' => now()->subDays(2),
                'graded_at' => now()->subDay(),
                'graded_by' => $teacher1->id,
            ]
        );

        // $students[1] (Citra) submits but pending grading
        Submission::firstOrCreate(
            ['assignment_id' => $assignment1->id, 'student_id' => $students[1]->id],
            [
                'content' => null,
                'file_path' => 'submissions/dummy2.zip',
                'status' => SubmissionStatus::Submitted,
                'submitted_at' => now()->subHours(5),
            ]
        );

        // $students[0] submits assignment 2 (text) pending grading
        Submission::firstOrCreate(
            ['assignment_id' => $assignment2->id, 'student_id' => $students[0]->id],
            [
                'content' => '1. Install Composer 2. composer create-project laravel/laravel myapp',
                'status' => SubmissionStatus::Submitted,
                'submitted_at' => now()->subHours(10),
            ]
        );

        $this->command->info('✅ Sample submissions created.');
        $this->command->newLine();
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin',    'admin@eduzy.test',    'password'],
                ['Teacher',  'teacher@eduzy.test',  'password'],
                ['Teacher',  'teacher2@eduzy.test', 'password'],
                ['Student',  'student@eduzy.test',  'password'],
                ['Student',  'student2@eduzy.test', 'password'],
            ]
        );
    }
}
