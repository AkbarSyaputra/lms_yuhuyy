<?php

namespace App\Http\Controllers;

use App\Actions\Submissions\CreateSubmissionAction;
use App\Actions\Submissions\GradeSubmissionAction;
use App\Http\Requests\GradeSubmissionRequest;
use App\Http\Requests\StoreSubmissionRequest;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Submission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class SubmissionController extends Controller
{
    use AuthorizesRequests;

    /**
     * Show the assignment details and submission form for a student.
     */
    public function showAssignment(Course $course, Assignment $assignment): Response
    {
        // Check if student can view/create submission (requires enrollment)
        $this->authorize('create', [Submission::class, $assignment]);

        // Eager load necessary relationships
        $assignment->load('course');

        // Check if student already submitted
        $submission = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', request()->user()->id)
            ->first();

        // If file exists, append URL
        if ($submission && $submission->file_path) {
            $submission->file_url = route('submissions.download', $submission);
        }

        return Inertia::render('Assignments/Show', [
            'course' => $course,
            'assignment' => $assignment,
            'submission' => $submission,
        ]);
    }

    /**
     * Store or update a student's submission.
     */
    public function store(StoreSubmissionRequest $request, Course $course, Assignment $assignment, CreateSubmissionAction $action): RedirectResponse
    {
        $this->authorize('create', [Submission::class, $assignment]);

        $action->execute($request->user()->id, $assignment, $request->toDto());

        return back()->with('success', 'Assignment submitted successfully.');
    }

    /**
     * Teacher dashboard to view all submissions for an assignment.
     */
    public function index(Course $course, Assignment $assignment): Response
    {
        // Teacher must be able to update the course to view its submissions
        $this->authorize('update', $course);

        $assignment->load('course');

        $submissions = Submission::with('student')
            ->where('assignment_id', $assignment->id)
            ->latest('submitted_at')
            ->get();

        return Inertia::render('submissions/Index', [
            'course' => $course,
            'assignment' => $assignment,
            'submissions' => $submissions,
        ]);
    }

    /**
     * Teacher view to grade a specific submission.
     */
    public function show(Course $course, Assignment $assignment, Submission $submission): Response
    {
        $this->authorize('grade', $submission);

        $assignment->load('course');
        $submission->load('student', 'gradedBy');

        if ($submission->file_path) {
            $submission->file_url = route('submissions.download', $submission);
        }

        return Inertia::render('submissions/Show', [
            'course' => $course,
            'assignment' => $assignment,
            'submission' => $submission,
        ]);
    }

    /**
     * Submit a grade for a submission.
     */
    public function grade(GradeSubmissionRequest $request, Course $course, Assignment $assignment, Submission $submission, GradeSubmissionAction $action): RedirectResponse
    {
        $this->authorize('grade', $submission);

        $action->execute($submission, $request->user()->id, $request->toDto());

        return redirect()->route('assignments.submissions.index', [$course, $assignment])
            ->with('success', 'Submission graded successfully.');
    }

    /**
     * Download a submission file.
     */
    public function download(Submission $submission)
    {
        $this->authorize('view', $submission);

        if (! $submission->file_path || ! Storage::disk('private')->exists($submission->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('private')->download($submission->file_path);
    }
}
