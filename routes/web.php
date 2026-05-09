<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CourseCategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    // Phase 2: User Management
    Route::resource('users', UserController::class);

    // Phase 3: Course & Category Management
    Route::resource('categories', CourseCategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('courses', CourseController::class);

    // Phase 4: Course Materials & Assignments (nested under courses)
    Route::prefix('courses/{course}')->group(function () {
        Route::resource('materials', MaterialController::class)->except(['index', 'show']);
        Route::post('materials/reorder', [MaterialController::class, 'reorder'])->name('courses.materials.reorder');
        Route::resource('assignments', AssignmentController::class)->except(['index', 'show']);

        // Phase 5: Student Enrollments
        Route::post('enroll', [EnrollmentController::class, 'store'])->name('courses.enroll');

        // Phase 5: Submissions
        Route::get('assignments/{assignment}/submissions/create', [SubmissionController::class, 'showAssignment'])->name('assignments.show');
        Route::post('assignments/{assignment}/submissions', [SubmissionController::class, 'store'])->name('submissions.store');

        Route::get('assignments/{assignment}/submissions', [SubmissionController::class, 'index'])->name('assignments.submissions.index');
        Route::get('assignments/{assignment}/submissions/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');
        Route::patch('assignments/{assignment}/submissions/{submission}/grade', [SubmissionController::class, 'grade'])->name('submissions.grade');
    });

    // Phase 5: Download Submission
    Route::get('submissions/{submission}/download', [SubmissionController::class, 'download'])->name('submissions.download');
});

require __DIR__.'/settings.php';
