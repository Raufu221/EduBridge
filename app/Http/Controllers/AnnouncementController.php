<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Course;
use App\Models\User;
use App\Notifications\NewCourseAnnouncementNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AnnouncementController extends Controller
{
    public function index()
    {
        // Instructor's own announcements + Global Admin announcements
        $announcements = Announcement::where(function($q) {
                $q->where('instructor_id', auth()->id())
                  ->orWhere(function($sq) {
                      $sq->whereNull('instructor_id')
                         ->whereNull('course_id')
                         ->whereIn('target_audience', ['All', 'Instructor']);
                  });
            })
            ->with('course')
            ->latest()
            ->get();
        
        $courses = Course::where('instructor_id', auth()->id())->get();
        
        return view('instructor.announcements', compact('announcements', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $course = Course::findOrFail($request->course_id);
        
        // Security check
        if ($course->instructor_id !== auth()->id()) {
            abort(403);
        }

        $announcement = Announcement::create([
            'course_id' => $request->course_id,
            'instructor_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        // Get all enrolled students
        $students = User::whereHas('enrollments', function($q) use ($course) {
            $q->where('course_id', $course->id);
        })->get();

        // Send Notifications
        Notification::send($students, new NewCourseAnnouncementNotification($announcement));

        return back()->with('success', 'Announcement posted and notifications sent to ' . $students->count() . ' students!');
    }

    /**
     * Display a specific announcement for the learner.
     */
    public function showLearner(Course $course, Announcement $announcement)
    {
        return view('learner.announcement-detail', compact('course', 'announcement'));
    }
}
