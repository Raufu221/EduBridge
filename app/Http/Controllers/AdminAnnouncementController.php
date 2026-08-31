<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\AdminBroadcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AdminAnnouncementController extends Controller
{
    /**
     * Show the broadcast form and history.
     */
    public function create()
    {
        $announcements = \App\Models\Announcement::whereNull('course_id')
            ->whereNull('instructor_id')
            ->latest()
            ->get();

        return view('admin.announcements.create', compact('announcements'));
    }

    /**
     * Broadcast the announcement to selected users.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target' => 'required|in:all,learner,instructor',
        ]);

        // 1. Save to database as a record
        \App\Models\Announcement::create([
            'title' => $request->title,
            'content' => $request->message,
            'target_audience' => $request->target,
            'course_id' => null,
            'instructor_id' => null,
        ]);

        // 2. Filter users by role for notifications
        $query = User::query();

        if ($request->target === 'learner') {
            $query->where('role', 'student');
        } elseif ($request->target === 'instructor') {
            $query->where('role', 'instructor');
        }

        $users = $query->get();

        $details = [
            'title' => $request->title,
            'message' => $request->message,
            'sender_name' => auth()->user()->name,
        ];

        // 3. Send notifications
        Notification::send($users, new AdminBroadcast($details));

        return back()->with('success', 'Broadcast sent successfully to ' . $users->count() . ' users!');
    }
}
