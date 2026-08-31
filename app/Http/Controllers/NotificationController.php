<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a list of all notifications for the user.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(15);
        Auth::user()->unreadNotifications->markAsRead();
        
        // Show Instructor view if they are an instructor
        if (Auth::user()->role === 'instructor') {
            return view('instructor.notifications', compact('notifications'));
        }

        // Otherwise show Learner view
        return view('learner.notifications', compact('notifications'));
    }

    /**
     * Clear all notifications.
     */
    public function clearAll()
    {
        Auth::user()->notifications()->delete();
        return back()->with('success', 'All notifications cleared.');
    }

    /**
     * Mark a specific notification as read and redirect.
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // Support AJAX response
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        $data = $notification->data;
        $link = $data['link'] ?? null;
        $courseId = $data['course_id'] ?? null;

        // If it's a student and it's an announcement notification
        if (Auth::user()->role !== 'instructor') {
            
            $type = $data['type'] ?? 'system';

            // Priority 1: If it's an announcement type, try to find the announcement ID
            if ($type === 'announcement' && $courseId) {
                // We need to find the announcement. For now, we'll try to find the latest one for this course
                $announcement = \App\Models\Announcement::where('course_id', $courseId)->latest()->first();
                if ($announcement) {
                    return redirect()->route('learner.course.announcement.show', ['course' => $courseId, 'announcement' => $announcement->id]);
                }
            }

            // Priority 2: Admin Broadcasts go to the main dashboard (where the widget is)
            if ($type === 'admin_broadcast') {
                return redirect()->route('learner.dashboard');
            }

            // Priority 3: Use course_id if available (fallback)
            if ($courseId) {
                return redirect()->route('learner.course.viewer', ['course' => $courseId]);
            }

            // Priority 2: If link is an instructor link, or missing, try to find course from message
            if (!$link || strpos($link, '/instructor') !== false) {
                $message = $data['message'] ?? '';
                
                // Try to find a course that matches the name in the message
                // Format is usually "New announcement posted in [Course Name]"
                if (preg_match('/in (.*)$/', $message, $matches)) {
                    $courseName = trim($matches[1]);
                    $course = \App\Models\Course::where('title', 'LIKE', "%{$courseName}%")->first();
                    if ($course) {
                        return redirect()->route('learner.course.viewer', ['course' => $course->id]);
                    }
                }

                // Fallback to learner dashboard
                return redirect()->route('dashboard');
            }
        }

        // Default redirect
        if (!$link) {
            return Auth::user()->role === 'instructor' 
                ? redirect()->route('instructor.dashboard') 
                : redirect()->route('dashboard');
        }

        return redirect($link);
    }
}
