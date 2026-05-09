<?php

namespace App\Http\Controllers;

use App\Actions\Dashboard\GetStudentStatsAction;
use App\Actions\Dashboard\GetTeacherStatsAction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Request $request,
        GetStudentStatsAction $studentStatsAction,
        GetTeacherStatsAction $teacherStatsAction
    ): Response {
        $user = $request->user();

        $studentStats = null;
        $teacherStats = null;

        if ($user->hasRole('student')) {
            $studentStats = $studentStatsAction->execute($user->id);
        }

        if ($user->hasRole('teacher') || $user->hasRole('admin')) {
            // Admin might also see teacher stats if they created courses, or we can just load it
            $teacherStats = $teacherStatsAction->execute($user->id);
        }

        return Inertia::render('Dashboard', [
            'studentStats' => $studentStats,
            'teacherStats' => $teacherStats,
        ]);
    }
}
