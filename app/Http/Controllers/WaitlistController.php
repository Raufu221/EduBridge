<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Waitlist;
use Illuminate\Support\Facades\Auth;

class WaitlistController extends Controller
{
    public function store(Request $request, Course $course)
    {
        // 1. Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to join the waitlist.');
        }

        $user = Auth::user();

        // 2. Check if the course is actually full
        $enrollmentsCount = $course->enrollments()->count();
        if (!$course->max_students || $enrollmentsCount < $course->max_students) {
            return back()->with('error', 'Seats are still available for this course! You can enroll directly.');
        }
        

        // 3. Check for existing waitlist record
        $waitlist = Waitlist::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($waitlist) {
            // If they are already waiting and have NOT been notified yet, don't duplicate
            if (is_null($waitlist->notified_at)) {
                return back()->with('info', 'You are already on the active waitlist for this course.');
            }
            
            // If they WERE notified but the course filled up again, they can re-join!
            $waitlist->update(['notified_at' => null]);
            return back()->with('success', 'You have re-joined the waitlist! We will notify you when seats open up again.');
        }

        // 4. Create a new waitlist record
        Waitlist::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'notified_at' => null,
        ]);

        return back()->with('success', 'You have been added to the waitlist! We will notify you if seats open up.');
    }

    public function destroy(Course $course)
    {
        // 1. Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = Auth::user();

        // 2. Find and delete the waitlist record
        $waitlist = Waitlist::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($waitlist) {
            $waitlist->delete();
            return back()->with('success', 'You have successfully left the waitlist for this course.');
        }

        return back()->with('info', 'You were not on the waitlist for this course.');
    }
}
