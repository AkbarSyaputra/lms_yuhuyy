<?php

namespace App\Http\Controllers;

use App\Actions\Courses\CreateCourseAction;
use App\Actions\Courses\DeleteCourseAction;
use App\Actions\Courses\UpdateCourseAction;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use App\Models\Enrollment;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly CourseRepositoryInterface $courseRepository,
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {}

    public function index(): Response
    {
        $this->authorize('viewAny', Course::class);

        return Inertia::render('Courses/Index', [
            'courses' => $this->courseRepository->getAllPaginated(),
            'categories' => $this->categoryRepository->getAll(),
            'filters' => request()->only('filter', 'sort'),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Course::class);

        return Inertia::render('Courses/Create', [
            'categories' => $this->categoryRepository->getAll(),
        ]);
    }

    public function store(StoreCourseRequest $request, CreateCourseAction $action): RedirectResponse
    {
        $this->authorize('create', Course::class);

        $action->execute($request->toDto());

        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function show(Course $course): Response
    {
        $this->authorize('view', $course);

        $course->load([
            'category',
            'creator',
            'materials' => fn ($q) => $q->orderBy('order'),
            'assignments' => fn ($q) => $q->orderBy('due_date'),
        ]);

        $isStudent = false;
        $isTeacher = false;
        $isAdmin = false;
        $isEnrolled = false;

        if (request()->user()) {
            $isStudent = request()->user()->hasRole('student');
            $isTeacher = request()->user()->hasRole('teacher');
            $isAdmin = request()->user()->hasRole('admin');

            if ($isStudent) {
                $isEnrolled = Enrollment::where('course_id', $course->id)
                    ->where('user_id', request()->user()->id)
                    ->exists();
            }
        }

        return Inertia::render('Courses/Show', [
            'course' => $course,
            'isEnrolled' => $isEnrolled,
            'isStudent' => $isStudent,
            'isTeacher' => $isTeacher,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function edit(Course $course): Response
    {
        $this->authorize('update', $course);

        return Inertia::render('Courses/Edit', [
            'course' => $course,
            'categories' => $this->categoryRepository->getAll(),
        ]);
    }

    public function update(UpdateCourseRequest $request, Course $course, UpdateCourseAction $action): RedirectResponse
    {
        $this->authorize('update', $course);

        $action->execute($course, $request->toDto());

        return redirect()->route('courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course, DeleteCourseAction $action): RedirectResponse
    {
        $this->authorize('delete', $course);

        $action->execute($course);

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}
