<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseViewerController extends Controller
{
    public function show(Course $course, Lesson $lesson = null)
    {
        // Ensure user is enrolled
        $user = Auth::user();
        $enrollment = $user->enrollments()->where('course_id', $course->id)->first();
        if (!$enrollment) {
            return redirect()->route('courses.show', $course)->with('error', 'You must be enrolled to view this course.');
        }

        // --- TRACK LAST ACCESS ---
        $enrollment->update(['last_accessed_at' => now()]);

        // Load modules and lessons for sidebar
        $course->load(['modules.lessons']);
        
        if ($lesson) {
            $lesson->load(['quiz.questions.options', 'assignment']);
        }

        // Determine which lesson to display
        if (!$lesson) {
            // Find the first lesson of the first module if no lesson is provided
            $firstModule = $course->modules->first();
            if ($firstModule && $firstModule->lessons->count() > 0) {
                $lesson = $firstModule->lessons->first();
                return redirect()->route('learner.course.viewer', ['course' => $course->id, 'lesson' => $lesson->id]);
            }
        } else {
            // Ensure the lesson belongs to this course
            if ($lesson->module->course_id !== $course->id) {
                 abort(404);
            }
        }

        // Get user's completed lessons IDs for the sidebar checkmarks
        $completedLessonIds = $user->completedLessons()->pluck('lesson_id')->toArray();

        // Dynamically calculate progress percent to ensure instant dynamic update on completion
        $totalLessons = $course->modules()->withCount('lessons')->get()->sum('lessons_count');
        $courseLessonIds = $course->modules()->with('lessons')->get()->pluck('lessons')->flatten()->pluck('id');
        $completedLessonsCount = $user->completedLessons()->whereIn('lesson_id', $courseLessonIds)->count();
        $percent = $totalLessons > 0 ? round(($completedLessonsCount / $totalLessons) * 100) : 0;

        if ($enrollment->progress_percent !== $percent) {
            $enrollment->update(['progress_percent' => $percent]);
        }

        // Check certificate eligibility
        $isEligible = $enrollment->isCertificateEligible();
        $averageScore = $enrollment->calculateAverage();
        
        $certificate = \App\Models\Certificate::where('user_id', $user->id)->where('course_id', $course->id)->first();
        
        // Fetch existing review for this user and course
        $existingReview = \App\Models\Review::where('user_id', $user->id)->where('course_id', $course->id)->first();

        return view('learner.course-viewer', compact('course', 'lesson', 'completedLessonIds', 'isEligible', 'averageScore', 'certificate', 'percent', 'existingReview'));
    }

    public function markComplete(Request $request, Lesson $lesson)
    {
        $user = Auth::user();

        // Toggle or attach
        if (!$user->completedLessons()->where('lesson_id', $lesson->id)->exists()) {
            $user->completedLessons()->attach($lesson->id);
            return response()->json(['status' => 'completed', 'message' => 'Lesson marked as complete!']);
        }

        return response()->json(['status' => 'already_completed', 'message' => 'Lesson already completed.']);
    }
}
