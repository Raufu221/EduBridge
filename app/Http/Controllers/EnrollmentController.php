<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    use \App\Traits\HandlesEnrollment;

    public function store(Request $request, Course $course)
    {
        $user = Auth::user();

        // Prevent enrolling in own course or authors
        if ($user->id === $course->instructor_id) {
            return redirect()->back()->with('error', 'You cannot enroll in your own course.');
        }

        // Check if already enrolled
        if ($user->enrollments()->where('course_id', $course->id)->exists()) {
            return redirect()->route('learner.course.viewer', $course)->with('info', 'You are already enrolled in this course.');
        }
        

        // --- PHASE 3: PRICE SECURITY CHECK ---
        if ($course->price > 0) {
            return redirect()->route('learner.checkout.show', $course->id)->with('error', 'This is a paid course. Please complete the checkout process.');
        }

        // --- CAPACITY SECURITY CHECK ---
        if ($course->max_students !== null) {
            $currentEnrollments = Enrollment::where('course_id', $course->id)->count();
            if ($currentEnrollments >= $course->max_students) {
                return redirect()->back()->with('error', 'Sorry, this course is currently full and has reached its maximum capacity.');
            }
        }

        // Create enrollment and transaction via shared logic
        $this->completeEnrollmentLogic($user->id, $course->id, 0, 'FREE-' . uniqid(), 'free');

        return redirect()->route('learner.dashboard')->with('success', 'Successfully enrolled in ' . $course->title . '!');
    }
}
