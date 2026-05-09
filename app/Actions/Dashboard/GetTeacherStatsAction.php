<?php

namespace App\Actions\Dashboard;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Submission;

class GetTeacherStatsAction
{
    /**
     * Get dashboard statistics for a teacher.
     */
    public function execute(int $teacherId): array
    {
        // 1. Total Courses they teach
        $teacherCourseIds = Course::where('created_by', $teacherId)->pluck('id');
        $totalCourses = $teacherCourseIds->count();

        // 2. Total Students enrolled across their courses
        $totalStudents = Enrollment::whereIn('course_id', $teacherCourseIds)
            ->distinct('user_id')
            ->count('user_id');

        // 3. Pending Submissions to grade
        $assignmentIds = Assignment::whereIn('course_id', $teacherCourseIds)->pluck('id');

        $pendingGradingCount = Submission::whereIn('assignment_id', $assignmentIds)
            ->whereNull('score')
            ->count();

        // 4. Recent Submissions needing grading
        $recentPendingSubmissions = Submission::with(['assignment.course', 'student'])
            ->whereIn('assignment_id', $assignmentIds)
            ->whereNull('score')
            ->latest('submitted_at')
            ->take(5)
            ->get()
            ->map(function ($submission) {
                return [
                    'id' => $submission->id,
                    'assignment_id' => $submission->assignment_id,
                    'course_id' => $submission->assignment->course_id,
                    'assignment_title' => $submission->assignment->title,
                    'course_title' => $submission->assignment->course->title,
                    'student_name' => $submission->student->name,
                    'submitted_at' => $submission->submitted_at,
                ];
            });

        return [
            'total_courses_count' => $totalCourses,
            'total_students_count' => $totalStudents,
            'pending_grading_count' => $pendingGradingCount,
            'recent_pending_submissions' => $recentPendingSubmissions,
        ];
    }
}
