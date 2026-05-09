<?php

namespace App\Actions\Dashboard;

use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\Submission;

class GetStudentStatsAction
{
    /**
     * Get dashboard statistics for a student.
     */
    public function execute(int $studentId): array
    {
        // 1. Enrolled Courses count
        $enrolledCoursesCount = Enrollment::where('user_id', $studentId)->count();

        // 2. Pending Assignments (Assignments from enrolled courses where student has no submission)
        $enrolledCourseIds = Enrollment::where('user_id', $studentId)->pluck('course_id');

        $totalAssignments = Assignment::whereIn('course_id', $enrolledCourseIds)->count();
        $submittedAssignments = Submission::where('student_id', $studentId)
            ->whereIn('assignment_id', function ($query) use ($enrolledCourseIds) {
                $query->select('id')->from('assignments')->whereIn('course_id', $enrolledCourseIds);
            })->count();

        $pendingAssignmentsCount = max(0, $totalAssignments - $submittedAssignments);

        // 3. Recent Grades (latest 5 submissions that have been graded)
        $recentGrades = Submission::with(['assignment.course'])
            ->where('student_id', $studentId)
            ->whereNotNull('score')
            ->latest('updated_at')
            ->take(5)
            ->get()
            ->map(function ($submission) {
                return [
                    'assignment_title' => $submission->assignment->title,
                    'course_title' => $submission->assignment->course->title,
                    'score' => $submission->score,
                    'max_score' => $submission->assignment->max_score,
                    'graded_at' => $submission->updated_at,
                ];
            });

        return [
            'enrolled_courses_count' => $enrolledCoursesCount,
            'pending_assignments_count' => $pendingAssignmentsCount,
            'recent_grades' => $recentGrades,
        ];
    }
}
