<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;
use App\Models\Waitlist;

class LearnerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // 1. Get all enrollments with courses & instructors
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with(['course.instructor', 'course.category', 'certificate'])
            ->get();

        // 2. Process metrics and sync progress
        $totalProgress = 0;
        $inProgressCount = 0;
        $completedCount = 0;

        foreach ($enrollments as $enrollment) {
            $course = $enrollment->course;
            
            // Re-calculate progress to ensure accuracy on dashboard load
            $totalLessons = $course->modules()->withCount('lessons')->get()->sum('lessons_count');
            $courseLessonIds = $course->modules()->with('lessons')->get()->pluck('lessons')->flatten()->pluck('id');
            $completedLessonsCount = $user->completedLessons()->whereIn('lesson_id', $courseLessonIds)->count();
            
            $progress = $totalLessons > 0 ? round(($completedLessonsCount / $totalLessons) * 100) : 0;
            
            // Update enrollment record if mismatch found
            if ($enrollment->progress_percent !== $progress) {
                $enrollment->update(['progress_percent' => $progress]);
            }

            // Categorize for stats
            if ($progress == 100) {
                $completedCount++;
            } else {
                // If it's not 100%, it's either just started (0%) or in progress (>0)
                $inProgressCount++;
            }
            
            $totalProgress += $progress;

            // Attach dynamic properties for Blade
            $enrollment->progress = $progress;
            $enrollment->completed_lessons = $completedLessonsCount;
            $enrollment->total_lessons = $totalLessons;
            $enrollment->has_reviewed = $user->reviews()->where('course_id', $course->id)->exists();
            $enrollment->average_score = $enrollment->calculateAverage();
            $enrollment->is_eligible = $enrollment->isCertificateEligible();
        }

        // 3. Aggregate Stats
        $averageProgress = $enrollments->count() > 0 ? round($totalProgress / $enrollments->count()) : 0;
        
        // 4. "Jump Back In" - Most recently accessed enrollment (any progress)
        $recentEnrollment = $enrollments->sortByDesc('last_accessed_at')->first();

        // 5. Waitlist Items
        $waitlistedCourses = Waitlist::where('user_id', $user->id)
            ->with(['course.instructor', 'course.category'])
            ->get();

        // 6. Certificates
        $certificates = \App\Models\Certificate::where('user_id', $user->id)
            ->with(['course.instructor'])
            ->get();

        // 7. Announcements (Own courses + Admin Global)
        $courseIds = $enrollments->pluck('course_id');
        $announcements = \App\Models\Announcement::where(function($q) use ($courseIds) {
                $q->whereIn('course_id', $courseIds)
                  ->orWhere(function($sq) {
                      $sq->whereNull('course_id')
                         ->whereNull('instructor_id')
                         ->whereIn('target_audience', ['All', 'Learner']); // Respect audience
                  });
            })
            ->latest()
            ->limit(5)
            ->get();

        return view('learner.dashboard', [
            'enrollments' => $enrollments,
            'averageProgress' => $averageProgress,
            'inProgressCount' => $inProgressCount,
            'completedCount' => $completedCount,
            'certificateCount' => $certificates->count(),
            'recentEnrollment' => $recentEnrollment,
            'waitlistedCourses' => $waitlistedCourses,
            'certificates' => $certificates,
            'announcements' => $announcements,
        ]);
    }
}
