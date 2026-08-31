<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Support\Str;
use App\Models\Module;
use App\Models\Lesson;
use App\Mail\WaitlistSeatOpened;
use App\Notifications\AssignmentGradedNotification;
use App\Notifications\WaitlistSeatOpenedNotification;
use App\Notifications\CourseAccessRevokedNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\PayoutRequest;
use App\Models\Transaction;
use App\Models\Enrollment;
use App\Models\Review;
use App\Models\User;
use App\Models\Quiz;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;


class InstructorController extends Controller
{
    // 1. Loads the Dashboard
    public function dashboard()
    {
        $instructor_id = auth()->id();
        
        $activeCourses = Course::where('instructor_id', $instructor_id)
                            ->where('is_published', true)->count();
        $totalCourses = Course::where('instructor_id', $instructor_id)->count();

        // Calculate actual unique student enrollments for this instructor's courses
        $courseIds = Course::where('instructor_id', $instructor_id)->pluck('id');
        $totalStudents = \App\Models\Enrollment::whereIn('course_id', $courseIds)->distinct('user_id')->count('user_id');
        
        // 3. Financials
        $totalRevenue = \App\Models\Transaction::whereIn('course_id', $courseIds)
            ->where('status', 'completed')
            ->sum('instructor_amount');

        // 4. Reputation
        $avgRating = \App\Models\Review::whereHas('course', function($q) use ($instructor_id) {
            $q->where('instructor_id', $instructor_id);
        })->avg('rating') ?: 0.0;

        // 5. Recent Enrollments (Optimized)
        $recentStudents = \App\Models\Enrollment::whereIn('course_id', $courseIds)
            ->with(['user', 'course'])
            ->latest()
            ->limit(5)
            ->get();

        // 6. Recent Global Announcements
        $announcements = \App\Models\Announcement::whereNull('course_id')
            ->whereNull('instructor_id')
            ->whereIn('target_audience', ['All', 'Instructor']) // Respect audience
            ->latest()
            ->limit(3)
            ->get();

        return view('instructor.dashboard', compact(
            'activeCourses', 
            'totalCourses', 
            'totalStudents', 
            'totalRevenue', 
            'avgRating', 
            'recentStudents',
            'announcements'
        ));
    }

    /**
     * Aggregates high-fidelity student engagement data for the Analytics Dashboard.
     */
    public function analytics()
    {
        $instructor_id = auth()->id();
        $courseIds = Course::where('instructor_id', $instructor_id)->pluck('id');

        // 1. Student KPIs
        $totalStudents = \App\Models\Enrollment::whereIn('course_id', $courseIds)
            ->distinct('user_id')
            ->count('user_id');
            
        $activeCourses = Course::where('instructor_id', $instructor_id)
            ->where('is_published', true)
            ->count();
            
        $avgRating = \App\Models\Review::whereHas('course', function($q) use ($instructor_id) {
            $q->where('instructor_id', $instructor_id);
        })->avg('rating') ?: 4.8;

        // Cumulative Completion Rate
        $totalEnrollmentsCount = \App\Models\Enrollment::whereIn('course_id', $courseIds)->count();
        $completedEnrollmentsCount = \App\Models\Enrollment::whereIn('course_id', $courseIds)
            ->where('progress_percent', 100)
            ->count();
        $completionRate = $totalEnrollmentsCount > 0 
            ? round(($completedEnrollmentsCount / $totalEnrollmentsCount) * 100, 1) 
            : 0;

        // 2. Enrollment Over Time (Last 6 Months)
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(now()->subMonths($i)->format('M Y'));
        }

        $monthlyEnrollments = \App\Models\Enrollment::whereIn('course_id', $courseIds)
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw("DATE_FORMAT(created_at, '%b %Y') as month, COUNT(*) as total")
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month');

        $enrollmentsData = $months->map(fn($m) => $monthlyEnrollments->get($m, 0));

        // 3. Category Breakdown (By Enrollment Count)
        $nicheBreakdown = \App\Models\Enrollment::whereIn('course_id', $courseIds)
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->join('categories', 'courses.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, COUNT(*) as count')
            ->groupBy('categories.name')
            ->get();

        // 4. Course Completion Rates (Horizontal Bar Chart)
        $courseCompletionData = \App\Models\Enrollment::whereIn('course_id', $courseIds)
            ->selectRaw('course_id, 
                COUNT(*) as total_enrollments, 
                SUM(CASE WHEN progress_percent = 100 THEN 1 ELSE 0 END) as completed_count')
            ->groupBy('course_id')
            ->with(['course' => function($q) {
                $q->select('id', 'title');
            }])
            ->get()
            ->map(function($item) {
                $item->rate = $item->total_enrollments > 0 
                    ? round(($item->completed_count / $item->total_enrollments) * 100, 1) 
                    : 0;
                return $item;
            })
            ->sortByDesc('rate')
            ->take(5);

        return view('instructor.analytics', compact(
            'totalStudents', 'activeCourses', 'avgRating', 'completionRate',
            'months', 'enrollmentsData', 'nicheBreakdown', 'courseCompletionData', 'totalEnrollmentsCount'
        ));
    }

    /**
     * Aggregates high-fidelity financial data for the Earnings Dashboard.
     */
    public function earnings()
    {
        $instructor_id = auth()->id();
        $courseIds = Course::where('instructor_id', $instructor_id)->pluck('id');

        // 1. Financial KPIs
        $totalEarnings = Transaction::whereIn('course_id', $courseIds)
            ->where('status', 'completed')
            ->sum('instructor_amount');
            
        $earningsThisMonth = Transaction::whereIn('course_id', $courseIds)
            ->where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('instructor_amount');

        // Available Balance = Total Earned - Sum of non-rejected requests
        $totalRequested = PayoutRequest::where('instructor_id', $instructor_id)
            ->where('status', '!=', 'rejected')
            ->sum('amount');
            
        $availableBalance = max(0, $totalEarnings - $totalRequested);

        // Check for existing pending/processing request
        $hasPendingRequest = PayoutRequest::where('instructor_id', $instructor_id)
            ->whereIn('status', ['pending', 'processing'])
            ->exists();

        // 2. Revenue Trend (Bar Chart - Last 6 Months)
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(now()->subMonths($i)->format('M Y'));
        }

        $monthlyEarnings = Transaction::whereIn('course_id', $courseIds)
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw("DATE_FORMAT(created_at, '%b %Y') as month, SUM(instructor_amount) as total")
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month');

        $earningsData = $months->map(fn($m) => $monthlyEarnings->get($m, 0));

        // 3. Course Revenue Split Ledger
        $courseLedger = Transaction::whereIn('course_id', $courseIds)
            ->where('status', 'completed')
            ->selectRaw('course_id, COUNT(*) as sales_count, SUM(gross_amount) as total_gross, SUM(commission_amount) as platform_fee, SUM(instructor_amount) as instructor_earning')
            ->groupBy('course_id')
            ->with(['course' => function($q) { $q->select('id', 'title'); }])
            ->get();

        // 4. Payout History
        $payoutRequests = PayoutRequest::where('instructor_id', $instructor_id)
            ->latest()
            ->get();

        return view('instructor.earnings', compact(
            'totalEarnings', 'earningsThisMonth', 'availableBalance', 'hasPendingRequest',
            'months', 'earningsData', 'courseLedger', 'payoutRequests'
        ));
    }

    public function storePayoutRequest(Request $request)
    {
        $instructor_id = auth()->id();

        // 1. Check for pending requests first
        $hasPending = PayoutRequest::where('instructor_id', $instructor_id)
            ->whereIn('status', ['pending', 'processing'])
            ->exists();

        if ($hasPending) {
            return back()->with('error', 'You already have a withdrawal request being processed.');
        }

        // 2. Calculate Available Balance
        $courseIds = Course::where('instructor_id', $instructor_id)->pluck('id');
        $totalEarnings = Transaction::whereIn('course_id', $courseIds)
            ->where('status', 'completed')
            ->sum('instructor_amount');
            
        $totalRequested = PayoutRequest::where('instructor_id', $instructor_id)
            ->where('status', '!=', 'rejected')
            ->sum('amount');
            
        $availableBalance = $totalEarnings - $totalRequested;

        // 3. Validation
        $rules = [
            'amount' => 'required|numeric|min:500|max:' . $availableBalance,
            'payout_method' => 'required|string|in:bkash,nagad,bank',
            'account_details' => 'required|string|max:500',
        ];

        if ($request->payout_method === 'bank') {
            $rules['account_number'] = 'required|digits:13';
            $rules['bank_name'] = 'required|string|max:255';
        } elseif (in_array($request->payout_method, ['bkash', 'nagad'])) {
            $rules['mobile_number'] = 'required|digits:11';
        }

        $request->validate($rules, [
            'amount.min' => 'The minimum withdrawal amount is 500৳.',
            'amount.max' => 'Your available balance is not sufficient for this request.',
            'account_number.digits' => 'The bank account number must be exactly 13 digits.',
            'account_number.required' => 'The bank account number is required.',
            'mobile_number.digits' => 'The mobile wallet number must be exactly 11 digits.',
        ]);

        // 4. Create Request
        PayoutRequest::create([
            'instructor_id' => $instructor_id,
            'amount' => $request->amount,
            'payout_method' => $request->payout_method,
            'account_details' => $request->account_details,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Your payout request of ৳' . number_format($request->amount, 2) . ' has been submitted for admin approval.');
    }

    // --- STUDENT MANAGEMENT ---
    public function students(Request $request)
    {
        $instructor_id = auth()->id();
        
        // 1. Get IDs of all courses owned by this instructor
        $courseIds = Course::where('instructor_id', $instructor_id)->pluck('id');

        // 2. Fetch Users who have enrollments in these courses, and eagerly load the associated enrollments & courses
        // We use query grouping and with() to solve the N+1 problem
        $studentsQuery = \App\Models\User::whereHas('enrollments', function($q) use ($courseIds) {
            $q->whereIn('course_id', $courseIds);
        })->with(['enrollments' => function($q) use ($courseIds) {
            // Eager load only the enrollments that belong to the instructor's courses
            $q->whereIn('course_id', $courseIds)->with('course');
        }, 'certificates' => function($q) use ($courseIds) {
            // Eager load certificates issued by this instructor's courses
            $q->whereIn('course_id', $courseIds);
        }]);

        // Option to filter by a specific course
        if ($request->has('course_id') && $request->course_id !== 'all') {
            $studentsQuery->whereHas('enrollments', function($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }

        $students = $studentsQuery->get();
        $ownedCourses = Course::where('instructor_id', $instructor_id)->get();

        // 3. Dynamically sync the progress_percent column for accuracy
        foreach($students as $student) {
            foreach($student->enrollments as $enrollment) {
                $totalLessons = \App\Models\Lesson::whereHas('module', function($q) use ($enrollment) {
                    $q->where('course_id', $enrollment->course_id);
                })->count();

                if ($totalLessons > 0) {
                    $completedLessons = $student->completedLessons()
                        ->whereHas('module', function($q) use ($enrollment) {
                            $q->where('course_id', $enrollment->course_id);
                        })->count();

                    $actualProgress = min(100, round(($completedLessons / $totalLessons) * 100));
                    
                    if ($enrollment->progress_percent !== $actualProgress) {
                        $enrollment->update(['progress_percent' => $actualProgress]);
                    }
                }
            }
        }

        // Must recalculate KPIs AFTER syncing the database
        $totalStudents = $students->count();
        $totalEnrollments = \App\Models\Enrollment::whereIn('course_id', $courseIds)->count();
        $avgCompletion = \App\Models\Enrollment::whereIn('course_id', $courseIds)->avg('progress_percent') ?? 0;

        return view('instructor.students.index', compact('students', 'ownedCourses', 'totalStudents', 'totalEnrollments', 'avgCompletion'));
    }

    public function revokeAccess(\App\Models\Enrollment $enrollment)
    {
        // Security checks: Does this enrollment belong to a course the instructor owns?
        if ($enrollment->course->instructor_id !== auth()->id()) {
            abort(403);
        }
        // Capture student User and Course details BEFORE we delete the enrollment
        $student = $enrollment->user;
        $courseTitle = $enrollment->course->title;
        // Trigger the notification
        $student->notify(new CourseAccessRevokedNotification($courseTitle));

        $enrollment->delete();

        return back()->with('success', 'Student access has been revoked successfully.');
    }

    // 2. Loads the "All Courses" page with real database data
    public function index()
    {
        // Fetch courses that belong to the logged-in instructor, newest first
        $courses = Course::where('instructor_id', auth()->id())
            ->withCount([
                'enrollments', 
                'waitlists'
            ])
            ->latest()
            ->get();
        
        return view('instructor.courses.index', compact('courses'));
    }

    // 3. Loads the "Create Course" form
    public function create()
    {
        // Fetch all categories to populate the dropdown
        $categories = Category::all(); 
        return view('instructor.course_create', compact('categories'));
    }

    // 4. Catches the form data and saves the new course to the database
    public function store(Request $request)
    {
        $category = Category::find($request->category_id);
        $minPrice = $category ? $category->min_price : 500;
        $maxPrice = $category ? $category->max_price : 15000;

        // Validate the user input
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // Max 5MB
            'max_students' => 'nullable|integer|min:1',
            'price' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request, $minPrice, $maxPrice, $category) {
                    if (!$request->is_free_flag && $value != 0) {
                        if ($value < $minPrice || $value > $maxPrice) {
                            $catName = $category ? $category->name : 'this category';
                            $fail("Paid courses in {$catName} must be priced between ৳" . number_format($minPrice) . " and ৳" . number_format($maxPrice) . ".");
                        }
                    }
                },
            ],
        ]);

        // Handle the Image Upload
        $imagePath = null;
        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('course_covers', 'public');
        }

        // Save the Course to the Database
        Course::create([
            'instructor_id' => auth()->id(), // Automatically assign to the logged-in instructor
            'category_id' => $request->category_id,
            'title' => $request->title,
            'max_students' => $request->max_students,
            'slug' => Str::slug($request->title) . '-' . uniqid(), // Converts "My Course" to "my-course-123"
            'description' => $request->description,
            'what_you_will_learn' => $request->what_you_will_learn,
            'requirements' => $request->requirements,
            'target_audience' => $request->target_audience,
            'level' => $request->level ?? 'beginner',
            'price' => $request->is_free_flag ? 0 : ($request->price ?? 0),
            'cover_image' => $imagePath,
            'is_published' => false, // Default to draft
            'is_submitted' => false,
        ]);

        // Redirect them to the "All Courses" page
        return redirect()->route('instructor.courses.index')->with('success', 'Course Draft Created Successfully!');
    }

    // Updates the course settings in the database
    public function update(Request $request, Course $course)
    {
        if ($course->instructor_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $category = Category::find($request->category_id);
        $minPrice = $category ? $category->min_price : 500;
        $maxPrice = $category ? $category->max_price : 15000;

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'max_students' => 'nullable|integer|min:1',
            'price' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request, $minPrice, $maxPrice, $category) {
                    if (!$request->is_free_flag && $value != 0) {
                        if ($value < $minPrice || $value > $maxPrice) {
                            $catName = $category ? $category->name : 'this category';
                            $fail("Paid courses in {$catName} must be priced between ৳" . number_format($minPrice) . " and ৳" . number_format($maxPrice) . ".");
                        }
                    }
                },
            ],
        ]);

        $oldMax = $course->max_students;
        $newMax = $request->max_students;


        $isResubmitting = !empty($course->admin_feedback);

        $updateData = [
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'what_you_will_learn' => $request->what_you_will_learn,
            'requirements' => $request->requirements,
            'target_audience' => $request->target_audience,
            'level' => $request->level ?? 'beginner',
            'price' => $request->is_free_flag ? 0 : ($request->price ?? 0),
            'max_students' => $newMax,
        ];

        if ($isResubmitting) {
            $updateData['is_submitted'] = true;
            $updateData['admin_feedback'] = null;
        }

        if ($request->has('slug') && $request->slug !== $course->slug) {
            $updateData['slug'] = Str::slug($request->slug);
        }

        if ($request->hasFile('cover_image')) {
            $updateData['cover_image'] = $request->file('cover_image')->store('course_covers', 'public');
        }

        $course->update($updateData);

        Log::info('Course capacity updated for course ID: ' . $course->id . '. Old: ' . $oldMax . ', New: ' . $newMax);

        // --- WAITLIST NOTIFICATION LOGIC ---
        // Parse values to ensure accurate comparisons
        $oldMaxVal = $oldMax !== null ? (int)$oldMax : null;
        $newMaxVal = $newMax !== null && $newMax !== '' ? (int)$newMax : null;

        // Capacity is increased 
        $capacityIncreased = false;
        if ($oldMaxVal !== null && $newMaxVal !== null && $newMaxVal > $oldMaxVal) {
            $capacityIncreased = true;
        } elseif ($oldMaxVal !== null && $newMaxVal === null) {
            $capacityIncreased = true;
        } elseif ($oldMaxVal === null && $newMaxVal !== null) {
            $capacityIncreased = true;
        }

        $notifiedCount = 0;
        if ($capacityIncreased) {
            Log::info('Capacity increased. Fetching waitlist...');
            $waitlistRecords = $course->waitlists()
                ->with('user')
                ->get();
            
            Log::info('Found ' . $waitlistRecords->count() . ' active waitlist records.');

            foreach ($waitlistRecords as $record) {
                $emailSuccess = false;
                $notifiedSuccess = false;

                try {
                    // Switching from queue() to send() for instant delivery in this local practicum environment!
                    Mail::to($record->user->email)->send(new WaitlistSeatOpened($course, $record->user));
                    $emailSuccess = true;
                } catch (\Exception $e) {
                    Log::error("Failed to send waitlist seat notification email to {$record->user->email}: " . $e->getMessage());
                }
                
                try {
                    // Send an in-app database notification to the student so they see it in their dashboard
                    $record->user->notify(new WaitlistSeatOpenedNotification($course));
                    $notifiedSuccess = true;
                } catch (\Exception $e) {
                    Log::error("Failed to send waitlist in-app notification to {$record->user->email}: " . $e->getMessage());
                }
                
                if ($emailSuccess || $notifiedSuccess) {
                    // Mark them as notified so they don't get spammed if instructor clicks save again
                    $record->update(['notified_at' => now()]);
                    $notifiedCount++;
                }
            }
        }

        // Direct back to stay on the same builder tab with success
        if ($notifiedCount > 0) {
            return back()->with('success', "Course Settings Updated Successfully! {$notifiedCount} waitlisted student(s) have been notified via email & in-app alerts.");
        }

        return back()->with('success', 'Course Settings Updated Successfully!');
    }

    // Rule 1 & 2: Soft Delete a course (only if it has 0 students)
    public function destroy(Course $course)
    {
        if ($course->instructor_id !== auth()->id()) abort(403);

        // Checking if course has students. (Since Enrollment model isn't built yet, this defaults to false securely)
        $hasStudents = method_exists($course, 'enrollments') ? $course->enrollments()->exists() : false;

        if ($hasStudents) {
            return back()->withErrors(['error' => 'You cannot delete a course that has enrolled students. You can only unpublish it.']);
        }

        $course->delete(); // Soft delete because 'SoftDeletes' trait is on the Course model

        return redirect()->route('instructor.courses.index')->with('success', 'Course has been moved to trash.');
    }

    // Rule 3: Unpublish a course (if it has students and cannot be deleted)
    public function unpublish(Course $course)
    {
        if ($course->instructor_id !== auth()->id()) abort(403);

        $course->update(['is_published' => false]);

        return redirect()->route('instructor.courses.index')->with('success', 'Course has been unpublished. Existing students can still access it.');
    }

   // 5. Loads the drag-and-drop Curriculum Builder for a SPECIFIC course
    public function builder(Course $course)
    {
        // Make sure the logged-in instructor actually owns this course!
        if ($course->instructor_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Load the course along with its modules and lessons, sorted by their order
        $course->load(['modules.lessons']);
        
        $categories = Category::all();

        return view('instructor.courses.builder', compact('course', 'categories'));
    }
    // Saves a new Module to the database
    public function storeModule(Request $request, Course $course)
    {
        // Security check
        if ($course->instructor_id !== auth()->id()) abort(403);

        $request->validate(['title' => 'required|string|max:255']);

        $course->modules()->create([
            'title' => $request->title,
            'order' => $course->modules()->count() + 1, // Puts it at the bottom of the list
        ]);

        return back()->with('success', 'Module added successfully!');
    }

    // Saves a new Lesson to the database
    public function storeLesson(Request $request, Course $course, Module $module)
    {
        // Security check
        if ($course->instructor_id !== auth()->id() || $module->course_id !== $course->id) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,article,quiz,assignment,resource',
            'duration' => 'nullable|string|max:255',
            'video_url' => 'nullable|url|max:255',
            'video_file' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:512000',
            'content' => 'nullable|string', 
            'transcript' => 'nullable|string',
            'resource_file' => 'nullable|file|max:20480',
            'time_limit_minutes' => 'nullable|integer',
            'passing_percent' => 'nullable|numeric|min:0|max:100',
            'total_marks' => 'nullable|integer',
            'passing_marks' => 'nullable|integer',
        ]);

        $resourcePath = null;
        $resourceName = null;

        if ($request->hasFile('resource_file')) {
            $resourcePath = $request->file('resource_file')->store('lesson_resources', 'public');
            $resourceName = $request->file('resource_file')->getClientOriginalName();
        }

        $videoPath = null;
        if ($request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('lesson_videos', 'public');
        }

        $lesson = $module->lessons()->create([
            'title' => $request->title,
            'type' => $request->type,
            'duration' => $request->duration,
            'video_url' => $request->video_url,
            'video_path' => $videoPath,
            'content' => $request->content,
            'transcript' => $request->transcript,
            'resource_file' => $resourcePath,
            'resource_name' => $resourceName,
            'order' => $module->lessons()->count() + 1,
        ]);

        if ($request->type === 'quiz') {
            $lesson->quiz()->create([
                'time_limit_minutes' => $request->time_limit_minutes,
                'passing_percent' => $request->passing_percent ?? 80,
            ]);
        } elseif ($request->type === 'assignment') {
            $lesson->assignment()->create([
                'total_marks' => $request->total_marks ?? 100,
                'passing_marks' => $request->passing_marks ?? 60,
            ]);
        }

        return back()->with('success', 'Lesson added successfully!');
    }

    // Updates a Module
    public function updateModule(Request $request, Course $course, Module $module)
    {
        if ($course->instructor_id !== auth()->id() || $module->course_id !== $course->id) abort(403);
        $request->validate(['title' => 'required|string|max:255']);
        $module->update(['title' => $request->title]);
        return back()->with('success', 'Module updated successfully!');
    }

    // Deletes a Module
    public function destroyModule(Course $course, Module $module)
    {
        if ($course->instructor_id !== auth()->id() || $module->course_id !== $course->id) abort(403);
        $module->delete();
        return back()->with('success', 'Module deleted successfully!');
    }

    // Updates a Lesson
    public function updateLesson(Request $request, Course $course, Module $module, Lesson $lesson)
    {
        if ($course->instructor_id !== auth()->id() || $module->course_id !== $course->id || $lesson->module_id !== $module->id) abort(403);
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,article,quiz,assignment,resource',
            'duration' => 'nullable|string|max:255',
            'video_url' => 'nullable|url|max:255',
            'video_file' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:512000',
            'content' => 'nullable|string',
            'transcript' => 'nullable|string',
            'resource_file' => 'nullable|file|max:20480',
            'time_limit_minutes' => 'nullable|integer',
            'passing_percent' => 'nullable|numeric|min:0|max:100',
            'total_marks' => 'nullable|integer',
            'passing_marks' => 'nullable|integer',
        ]);

        $updateData = [
            'title' => $request->title,
            'type' => $request->type,
            'duration' => $request->duration,
            'video_url' => $request->video_url,
            'content' => $request->content,
            'transcript' => $request->transcript,
        ];

        if ($request->hasFile('video_file')) {
            // Delete old video if exists
            if ($lesson->video_path) {
                Storage::disk('public')->delete($lesson->video_path);
            }
            $updateData['video_path'] = $request->file('video_file')->store('lesson_videos', 'public');
            // If we upload a file, we usually want to clear the URL to avoid confusion
            $updateData['video_url'] = null; 
        } elseif ($request->video_url) {
            // If they provided a URL, maybe they want to switch back from upload?
            if ($lesson->video_path) {
                Storage::disk('public')->delete($lesson->video_path);
                $updateData['video_path'] = null;
            }
        }

        if ($request->hasFile('resource_file')) {
            if ($lesson->resource_file) {
                Storage::disk('public')->delete($lesson->resource_file);
            }
            $updateData['resource_file'] = $request->file('resource_file')->store('lesson_resources', 'public');
            $updateData['resource_name'] = $request->file('resource_file')->getClientOriginalName();
        }

        $lesson->update($updateData);

        if ($request->type === 'quiz') {
            $lesson->quiz()->updateOrCreate(
                ['lesson_id' => $lesson->id],
                [
                    'time_limit_minutes' => $request->time_limit_minutes,
                    'passing_percent' => $request->passing_percent ?? 80,
                ]
            );
        } elseif ($request->type === 'assignment') {
            $lesson->assignment()->updateOrCreate(
                ['lesson_id' => $lesson->id],
                [
                    'total_marks' => $request->total_marks ?? 100,
                    'passing_marks' => $request->passing_marks ?? 50,
                ]
            );
        }

        return back()->with('success', 'Lesson updated successfully!');
    }

    // Deletes a Lesson
    public function destroyLesson(Course $course, Module $module, Lesson $lesson)
    {
        if ($course->instructor_id !== auth()->id() || $module->course_id !== $course->id || $lesson->module_id !== $module->id) abort(403);
        
        // Clean up files
        if ($lesson->video_path) {
            Storage::disk('public')->delete($lesson->video_path);
        }
        if ($lesson->resource_file) {
            Storage::disk('public')->delete($lesson->resource_file);
        }

        $lesson->delete();
        return back()->with('success', 'Lesson deleted successfully!');
    }

    // --- QUIZ BUILDER LOGIC ---

    public function buildQuiz(Course $course, Lesson $lesson)
    {
        if ($course->instructor_id !== auth()->id() || $lesson->type !== 'quiz') abort(403);
        $lesson->load('quiz.questions.options');
        return view('instructor.courses.quiz_builder', compact('course', 'lesson'));
    }

    public function storeQuestion(Request $request, Course $course, Lesson $lesson)
    {
        if ($course->instructor_id !== auth()->id() || $lesson->type !== 'quiz') abort(403);
        
        $request->validate([
            'question_text' => 'required|string',
            'points' => 'required|integer|min:1',
            'options' => 'required|array|min:2|max:4',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|min:0|max:3',
            'rationale' => 'nullable|string'
        ]);

        $quiz = $lesson->quiz;
        if (!$quiz) {
            $quiz = $lesson->quiz()->create(['passing_percent' => 80]); 
        }

        $question = $quiz->questions()->create([
            'question_text' => $request->question_text,
            'points' => $request->points,
            'rationale' => $request->rationale
        ]);

        foreach ($request->options as $index => $optionText) {
            $question->options()->create([
                'option_text' => $optionText,
                'is_correct' => ($index == $request->correct_option)
            ]);
        }

        return back()->with('success', 'Question added successfully!');
    }

    public function editQuestion(Course $course, Lesson $lesson, \App\Models\Question $question)
    {
        if ($course->instructor_id !== auth()->id() || $lesson->type !== 'quiz') abort(403);
        $question->load('options');
        return view('instructor.courses.quiz_question_edit', compact('course', 'lesson', 'question'));
    }

    public function updateQuestion(Request $request, Course $course, Lesson $lesson, \App\Models\Question $question)
    {
        if ($course->instructor_id !== auth()->id() || $lesson->type !== 'quiz') abort(403);
        
        $request->validate([
            'question_text' => 'required|string',
            'points' => 'required|integer|min:1',
            'options' => 'required|array|min:2|max:4',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|min:0|max:3',
            'rationale' => 'nullable|string'
        ]);

        $question->update([
            'question_text' => $request->question_text,
            'points' => $request->points,
            'rationale' => $request->rationale
        ]);

        // Recreate options
        $question->options()->delete();
        foreach ($request->options as $index => $optionText) {
            $question->options()->create([
                'option_text' => $optionText,
                'is_correct' => ($index == $request->correct_option)
            ]);
        }

        return redirect()->route('instructor.quiz.build', [$course->id, $lesson->id])->with('success', 'Question updated successfully!');
    }

    public function destroyQuestion(Course $course, Lesson $lesson, \App\Models\Question $question)
    {
        if ($course->instructor_id !== auth()->id() || $lesson->type !== 'quiz') abort(403);
        $question->delete();
        return back()->with('success', 'Question deleted successfully!');
    }

    // Submits the course for admin approval
    public function submit(Course $course)
    {
        // Security check
        if ($course->instructor_id !== auth()->id()) abort(403);

        // Optional: validate if course has at least one module
        if ($course->modules()->count() === 0) {
            return back()->withErrors(['error' => 'You must add at least one module before submitting.']);
        }

        $course->update(['is_submitted' => true]);

        return redirect()->route('instructor.courses.index')->with('success', 'Course submitted for admin approval!');
    }

    // Previews the course as a student would see it
    public function preview(Course $course)
    {
        if ($course->instructor_id !== auth()->id()) abort(403);

        $course->load(['modules.lessons'])->loadCount('enrollments');
        return view('instructor.courses.preview', compact('course'));
    }

    // --- GLOBAL ASSESSMENTS ---

    public function quizzes()
    {
        $quizzes = \App\Models\Quiz::whereHas('lesson.module.course', function($q) {
            $q->where('instructor_id', auth()->id());
        })->with(['lesson.module.course'])->get();
        
        $courses = \App\Models\Course::where('instructor_id', auth()->id())->get();
        return view('instructor.assessments.quizzes', compact('quizzes', 'courses'));
    }

    public function assignments()
    {
        $assignments = \App\Models\Assignment::whereHas('lesson.module.course', function($q) {
            $q->where('instructor_id', auth()->id());
        })->with(['lesson.module.course'])->get();
        
        $courses = \App\Models\Course::where('instructor_id', auth()->id())->get();
        return view('instructor.assessments.assignments', compact('assignments', 'courses'));
    }

    public function grading()
    {
        // Only fetch submissions that belong to this instructor's courses!
        $submissions = \App\Models\AssignmentSubmission::whereHas('assignment.lesson.module.course', function($q) {
            $q->where('instructor_id', auth()->id());
        })->with(['user', 'assignment.lesson'])->get();
        return view('instructor.assessments.grading', compact('submissions'));
    }

    public function storeGrade(Request $request, \App\Models\AssignmentSubmission $submission)
    {
        // Security check
        if ($submission->assignment->lesson->module->course->instructor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'marks_awarded' => 'required|numeric|min:0|max:' . ($submission->assignment->total_marks ?? 100),
            'feedback' => 'nullable|string'
        ]);

        $submission->update([
            'marks_awarded' => $request->marks_awarded,
            'feedback' => $request->feedback,
            'status' => 'graded'
        ]);

        // Trigger Notification
        $submission->user->notify(new AssignmentGradedNotification($submission));

        return back()->with('success', 'Assignment graded successfully and student notified!');
    }
    // --- REVIEWS & REPUTATION MANAGEMENT ---

    public function reviews(Request $request)
    {
        $instructor_id = auth()->id();
        
        // Fetch all reviews for all courses owned by this instructor
        $reviewsQuery = \App\Models\Review::whereHas('course', function($q) use ($instructor_id) {
            $q->where('instructor_id', $instructor_id);
        })->with(['user', 'course'])->latest();

        // Filters
        if ($request->get('filter') === 'unanswered') {
            $reviewsQuery->whereNull('instructor_reply');
        }
        if ($request->get('rating')) {
            $reviewsQuery->where('rating', $request->get('rating'));
        }

        $reviews = $reviewsQuery->paginate(15);
        
        // Calculate average rating across all courses
        $avgRating = \App\Models\Review::whereHas('course', function($q) use ($instructor_id) {
            $q->where('instructor_id', $instructor_id);
        })->avg('rating') ?: 0;

        return view('instructor.reviews.index', compact('reviews', 'avgRating'));
    }

    public function replyToReview(Request $request, \App\Models\Review $review)
    {
        // Security check: Does this review belong to a course the instructor owns?
        if ($review->course->instructor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'instructor_reply' => 'required|string|max:1000'
        ]);

        $review->update([
            'instructor_reply' => $request->instructor_reply
        ]);

        return back()->with('success', 'Your response has been posted!');
    }
}