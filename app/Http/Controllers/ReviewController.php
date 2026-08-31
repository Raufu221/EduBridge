<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display the public reviews page.
     */
    public function index()
    {
        $reviews = Review::with(['user', 'course'])->latest()->get();
        return view('reviews.index', compact('reviews'));
    }

    public function store(Request $request, Course $course)
    {
        $user = Auth::user();

        // 1. "Verified Buyer" Rule: Validate the user is enrolled
        if (!$user->enrollments()->where('course_id', $course->id)->exists()) {
            return back()->with('error', 'Only enrolled students can leave reviews.');
        }

        // 2. Input validation
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // 3. "One User, One Review" & "Update, Don't Duplicate" Rules
        // We use updateOrCreate to fulfill both requirements automatically
        Review::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return back()->with('success', 'Your review has been shared with the community!');
    }
}
