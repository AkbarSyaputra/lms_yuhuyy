<?php

namespace App\Http\Controllers;

use App\Actions\Assignments\CreateAssignmentAction;
use App\Actions\Assignments\DeleteAssignmentAction;
use App\Actions\Assignments\UpdateAssignmentAction;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Models\Assignment;
use App\Models\Course;
use App\Repositories\Contracts\AssignmentRepositoryInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AssignmentController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly AssignmentRepositoryInterface $assignmentRepository,
    ) {}

    public function create(Course $course): Response
    {
        $this->authorize('update', $course);

        return Inertia::render('Assignments/Create', [
            'course' => $course,
        ]);
    }

    public function store(StoreAssignmentRequest $request, Course $course, CreateAssignmentAction $action): RedirectResponse
    {
        $this->authorize('update', $course);

        $action->execute($course->id, $request->toDto());

        return redirect()->route('courses.show', $course)
            ->with('success', 'Assignment created successfully.');
    }

    public function edit(Course $course, Assignment $assignment): Response
    {
        $this->authorize('update', $assignment);

        return Inertia::render('Assignments/Edit', [
            'course' => $course,
            'assignment' => $assignment,
        ]);
    }

    public function update(UpdateAssignmentRequest $request, Course $course, Assignment $assignment, UpdateAssignmentAction $action): RedirectResponse
    {
        $this->authorize('update', $assignment);

        $action->execute($assignment, $request->toDto());

        return redirect()->route('courses.show', $course)
            ->with('success', 'Assignment updated successfully.');
    }

    public function destroy(Course $course, Assignment $assignment, DeleteAssignmentAction $action): RedirectResponse
    {
        $this->authorize('delete', $assignment);

        $action->execute($assignment);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Assignment deleted successfully.');
    }
}
