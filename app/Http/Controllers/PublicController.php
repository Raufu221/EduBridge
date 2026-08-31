<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Category;
use App\Models\User;
use App\Models\Review;

class PublicController extends Controller
{
    public function home()
    {
        // 1. Top Categories
        $categories = Category::withCount('courses')
            ->having('courses_count', '>', 0)
            ->orderBy('courses_count', 'desc')
            ->take(8)
            ->get();

        // 2. Latest Featured Courses
        $featuredCourses = Course::where('is_published', true)
            ->with(['instructor', 'category'])
            ->latest()
            ->take(6)
            ->get();

        // 3. Top 4 Instructors (Ordered by course count for now as a proxy for 'Top')
        $featuredInstructors = User::where('role', 'instructor')
            ->whereHas('courses', function($q) { $q->where('is_published', true); })
            ->withCount(['courses' => function($q) { $q->where('is_published', true); }])
            ->orderBy('courses_count', 'desc')
            ->take(4)
            ->get();

        // 4. Recent 5-Star Reviews
        $reviews = Review::with(['user', 'course'])
            ->where('rating', 5)
            ->where('is_hidden', false)
            ->latest()
            ->take(3)
            ->get();

        return view('welcome', compact('categories', 'featuredCourses', 'featuredInstructors', 'reviews'));
    }

    public function instructorsIndex()
    {
        $instructors = User::where('role', 'instructor')
            ->whereHas('courses', function($q) { $q->where('is_published', true); })
            ->withCount(['courses' => function($q) { $q->where('is_published', true); }])
            ->orderBy('courses_count', 'desc')
            ->paginate(12);

        return view('pages.instructors-index', compact('instructors'));
    }

    public function reviewsIndex()
    {
        $reviews = Review::with(['user', 'course'])
            ->where('rating', '>=', 4)
            ->where('is_hidden', false)
            ->latest()
            ->paginate(10);

        return view('pages.reviews-index', compact('reviews'));
    }

    public function courses(Request $request)
    {
        // Base Query: Only load explicitly published courses!
        $query = Course::where('is_published', true)->with(['instructor', 'category']);

        // 1. Search by Course Title or Instructor Name
        if ($request->has('search') && $request->get('search') !== '') {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('instructor', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Filter by Category slug
        if ($request->has('category') && $request->get('category') !== 'all') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->get('category'));
            });
        }

        // 3. Filter by Price Tier
        if ($request->has('price') && $request->get('price') !== 'all') {
            if ($request->get('price') === 'free') {
                $query->where('price', 0);
            }
            if ($request->get('price') === 'paid') {
                $query->where('price', '>', 0);
            }
        }

        // 4. Sorting logic
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $courses = $query->paginate(12)->withQueryString(); // Maintain search state on pagination
        
        // Fetch categories for the sidebar (only those that actually have active courses)
        $categories = Category::withCount(['courses' => function ($query) {
            $query->where('is_published', true);
        }])->having('courses_count', '>', 0)->orderBy('name')->get();

        return view('pages.courses', compact('courses', 'categories'));
    }

    public function show(Course $course)
    {
        // Security: Prevent public users from viewing unpublished drafts
        if (!$course->is_published) {
            abort(404, 'This course is currently unavailable.');
        }

        // Deep-load the syllabus curriculum structure for the frontend
        $course->load(['instructor', 'category', 'modules.lessons'])->loadCount('enrollments');

        return view('pages.course-details', compact('course'));
    }

    public function instructorProfile(User $user)
    {
        // For security, only allow viewing users who are actually instructors
        if ($user->role !== 'instructor') {
            abort(404, 'Expert profile not found.');
        }

        // Fetch published courses for this instructor with categories
        $courses = $user->courses()
            ->where('is_published', true)
            ->with(['category'])
            ->latest()
            ->get();


        // Calculate the total number of unique active students enrolled in all of the instructor's published courses
        $courseIds = $courses->pluck('id');
        $studentCount = 0;
        if ($courseIds->count() > 0) {
            $studentCount = \App\Models\Enrollment::whereIn('course_id', $courseIds)
                ->where('status', 'active')
                ->distinct('user_id')
                ->count('user_id');
        }
         // Calculate average rating across all courses
        $avgRating = \App\Models\Review::whereHas('course', function($q) use ($user) {
            $q->where('instructor_id', $user->id);
        })->avg('rating') ?: 0;

        return view('pages.instructor-profile', compact('user', 'courses', 'studentCount','avgRating'));
    }
}
