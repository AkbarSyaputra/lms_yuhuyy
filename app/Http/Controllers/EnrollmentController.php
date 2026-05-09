<?php

namespace App\Http\Controllers;

use App\Actions\Enrollments\EnrollStudentAction;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Course $course, EnrollStudentAction $action): RedirectResponse
    {
        $this->authorize('enroll', [Enrollment::class, $course]);

        try {
            $action->execute($request->user()->id, $course);

            return back()->with('success', 'You have successfully enrolled in this course.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
