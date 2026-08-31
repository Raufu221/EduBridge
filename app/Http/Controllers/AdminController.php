<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\Category;
use App\Models\InstructorApplication;
use App\Models\PayoutRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ApplicationApproved;
use App\Mail\ApplicationRejected;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Calculate the aggregate stats for the dashboard
        $totalPublishedCourses = Course::where('is_published', true)->count();
        // Pending courses are submitted but not yet published
        $totalPendingCourses = Course::where('is_submitted', true)->where('is_published', false)->count();
        $totalInstructors = User::where('role', 'instructor')->count();
        $totalStudents = User::where('role', 'student')->count();
        
        // Calculate actual platform revenue from commissions (Phase 3)
        $platformRevenue = \App\Models\Transaction::where('status', 'completed')->sum('commission_amount');

        return view('admin.dashboard', compact(
            'totalPublishedCourses', 
            'totalPendingCourses', 
            'totalInstructors', 
            'totalStudents',
            'platformRevenue'
        ));
    }
        public function courseApprovals()
    {
        // Get all courses that are submitted but not yet published
        $pendingCourses = Course::where('is_submitted', true)
                                ->where('is_published', false)
                                ->with(['instructor', 'category'])
                                ->get();
        
        return view('admin.courses.approvals', compact('pendingCourses'));
    }

    public function approveCourse(Course $course)
    {
        $course->update(['is_published' => true]);
        return redirect()->back()->with('success', 'Course approved and is now live!');
    }

    public function rejectCourse(Request $request, Course $course)
    {
        $request->validate([
            'reason' => 'required|string|max:1000'
        ]);

        // Send it back to draft mode by un-submitting it and providing feedback
        $course->update([
            'is_submitted' => false,
            'is_published' => false,
            'admin_feedback' => $request->reason
        ]);

        return redirect()->back()->with('success', 'Course rejected and feedback sent to the instructor.');
    }
        public function users(Request $request)
    {
        $query = User::withCount('courses');

        // Search by name or email
        if ($request->has('search') && $request->get('search') != '') {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        // Filter by role
        if ($request->has('role') && $request->get('role') != 'all') {
            $query->where('role', $request->get('role'));
        }

        // Return users, highest ID first (newest), paginated
        $users = $query->orderBy('id', 'desc')->paginate(12);
        
        return view('admin.users.index', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:student,instructor,admin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'active',
        ]);

        return back()->with('success', 'New user successfully created and manually added to the platform.');
    }

    public function promoteUser(User $user)
    {
        // Prevent modifying other admins
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Cannot modify administrator roles.');
        }

        // Toggle role between student and instructor
        $newRole = $user->role === 'instructor' ? 'student' : 'instructor';
        $user->update(['role' => $newRole]);
        
        $message = $newRole === 'instructor' ? 'User promoted to Instructor!' : 'User demoted to Student.';
        return redirect()->back()->with('success', $message);
    }

    public function suspendUser(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Cannot suspend administrator accounts.');
        }

        $newStatus = $user->status === 'active' ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);
        return redirect()->back()->with('success', "User account has been {$newStatus}.");
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Administrators cannot be deleted.');
        }
        
        $user->delete();
        return redirect()->back()->with('success', 'User safely soft-deleted from the active platform.');
    }

    public function applications()
    {
        $applications = InstructorApplication::with('user')->latest()->paginate(15);
        return view('admin.users.applications', compact('applications'));
    }

    public function approveApplication(InstructorApplication $application)
    {
        $tempPassword = null;

        // If Guest Application: Create User first
        if (!$application->user_id) {
            $tempPassword = Str::random(8);
            
            $user = User::create([
                'name' => $application->full_name,
                'email' => $application->email,
                'password' => Hash::make($tempPassword),
                'role' => 'instructor',
                'status' => 'active'
            ]);

            $application->update(['user_id' => $user->id]);
        } else {
            // Existing User: Just elevate role
            $application->user->update(['role' => 'instructor']);
        }

        $application->update(['status' => 'approved']);

        // Send Notification Email
        $recipient = $application->email ?? $application->user->email;
        try {
            Mail::to($recipient)->send(new ApplicationApproved($application, $tempPassword));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send application approval email to {$recipient}: " . $e->getMessage());
        }
        
        return back()->with('success', 'Application approved! Notification email sent and instructor account finalized.');
    }

    public function rejectApplication(Request $request, InstructorApplication $application)
    {
        $request->validate([
            'reason' => 'required|string|max:1000'
        ]);

        $application->update([
            'status' => 'rejected',
            'admin_feedback' => $request->reason
        ]);

        // Send Notification Email
        $recipient = $application->email ?? $application->user->email;
        try {
            Mail::to($recipient)->send(new ApplicationRejected($application));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send application rejection email to {$recipient}: " . $e->getMessage());
        }

        return back()->with('success', 'Application rejected and notification email sent with reasons.');
    }

    public function categories()
    {
        // Get all categories and count how many courses are using them
        $categories = Category::withCount('courses')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'min_price' => 'required|integer|min:0',
            'max_price' => 'required|integer|gt:min_price'
        ]);
        
        Category::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'min_price' => $request->min_price,
            'max_price' => $request->max_price
        ]);

        return redirect()->back()->with('success', 'New category created successfully!');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'min_price' => 'required|integer|min:0',
            'max_price' => 'required|integer|gt:min_price'
        ]);
        
        $category->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'min_price' => $request->min_price,
            'max_price' => $request->max_price
        ]);

        return redirect()->back()->with('success', 'Category updated successfully!');
    }

    public function destroyCategory(Category $category)
    {
        // Prevent deleting categories that already have courses attached to them!
        if ($category->courses()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete this category because courses are using it! Reassign the courses first.');
        }

        $category->delete();
        return redirect()->back()->with('success', 'Category deleted safely.');
    }

    public function showCategory(Category $category)
    {
        // Get all courses that belong to this category
        $courses = $category->courses()->with(['instructor'])->latest()->paginate(12);
        return view('admin.categories.show', compact('category', 'courses'));
    }

    public function previewCourse(Course $course)
    {
        // Admins can globally preview ANY course using a secure sandbox UI wrapped in the Admin Layout
        $course->load(['modules.lessons'])->loadCount('enrollments');
        return view('admin.courses.preview', compact('course'));
    }

    // --- CERTIFICATE MODERATION ---

    public function certificates(Request $request)
    {
        $query = \App\Models\Certificate::with(['user', 'course.instructor'])->latest();

        if ($request->get('search')) {
            $search = $request->get('search');
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('course', function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            })->orWhere('certificate_code', 'like', "%{$search}%");
        }

        $certificates = $query->paginate(15);
        
        return view('admin.certificates.index', compact('certificates'));
    }

    public function toggleCertificateValidity(\App\Models\Certificate $certificate)
    {
        $certificate->update(['is_valid' => !$certificate->is_valid]);
        $status = $certificate->is_valid ? 'restored' : 'revoked';
        return back()->with('success', "Certificate {$certificate->certificate_code} has been {$status}.");
    }

    // --- REVIEWS MODERATION ---

    public function reviews(Request $request)
    {
        $query = \App\Models\Review::with(['user', 'course.instructor'])->latest();

        if ($request->get('search')) {
            $search = $request->get('search');
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('course', function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        if ($request->get('rating')) {
            $query->where('rating', $request->get('rating'));
        }

        $reviews = $query->paginate(15);
        
        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggleReviewVisibility(\App\Models\Review $review)
    {
        $review->update(['is_hidden' => !$review->is_hidden]);
        $status = $review->is_hidden ? 'hidden' : 'visible';
        return back()->with('success', "Review is now {$status} to the public.");
    }

    public function destroyReview(\App\Models\Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review has been permanently deleted from the platform.');
    }

    // --- PHASE 3: PAYMENT MODERATION & ANALYTICS ---

    public function revenueAnalytics()
    {
        // 1. Monthly Platform Revenue (Commission only) for Bar Chart
        $monthlyRevenue = \App\Models\Transaction::where('status', 'completed')
            ->selectRaw("DATE_FORMAT(created_at, '%b %Y') as month_label, SUM(commission_amount) as total")
            ->groupBy('month_label')
            ->orderByRaw('MIN(created_at)')
            ->get();

        // 2. Revenue Breakdown by Course
        $courseBreakdown = \App\Models\Transaction::where('status', 'completed')
            ->selectRaw('course_id, COUNT(*) as sales_count, SUM(net_paid) as total_gross, SUM(commission_amount) as platform_earnings, SUM(instructor_amount) as instructor_earnings')
            ->groupBy('course_id')
            ->with('course')
            ->get();
        
        return view('admin.payments.revenue', compact('monthlyRevenue', 'courseBreakdown'));
    }

    public function payments(Request $request)
    {
        $query = \App\Models\Transaction::with(['user', 'course'])->latest();

        if ($request->get('status')) {
            $query->where('status', $request->get('status'));
        }

        $transactions = $query->paginate(15);
        
        return view('admin.payments.index', compact('transactions'));
    }

    public function approvePayment(\App\Models\Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Only pending transactions can be approved.');
        }

        // 1. Mark as completed
        $transaction->update([
            'status' => 'completed',
            'clearance_date' => now()->addDays(14)
        ]);

        // 2. Grant Enrollment
        \App\Models\Enrollment::updateOrCreate(
            ['user_id' => $transaction->user_id, 'course_id' => $transaction->course_id],
            ['status' => 'active']
        );

        return back()->with('success', 'Payment approved! The student now has full access to the course.');
    }

    public function rejectPayment(\App\Models\Transaction $transaction)
    {
        $transaction->update(['status' => 'rejected']);
        return back()->with('success', 'Payment has been rejected.');
    }

    /**
     * Defense Mode Simulator: Instantly completes ANY transaction.
     */
    public function simulateSuccess(\App\Models\Transaction $transaction)
    {
        $transaction->update([
            'status' => 'completed',
            'clearance_date' => now()->addDays(14),
            'gateway_ref' => 'SIMULATED-' . uniqid()
        ]);

        \App\Models\Enrollment::updateOrCreate(
            ['user_id' => $transaction->user_id, 'course_id' => $transaction->course_id],
            ['status' => 'active']
        );

        return back()->with('success', '[DEFENSE MODE] Transaction simulated as SUCCESS. Student enrolled.');
    }

    // --- MONETIZATION: PAYOUT MANAGEMENT ---

    public function payouts()
    {
        // Fetch pending/processing requests first, then others
        $pendingRequests = PayoutRequest::with('instructor')
            ->whereIn('status', ['pending', 'processing'])
            ->latest()
            ->get();

        $completedRequests = PayoutRequest::with('instructor')
            ->whereIn('status', ['paid', 'rejected'])
            ->latest()
            ->paginate(15);

        return view('admin.payouts', compact('pendingRequests', 'completedRequests'));
    }

    public function markPayoutPaid(Request $request, PayoutRequest $payout)
    {
        if ($payout->status === 'paid') {
            return back()->with('error', 'This payout has already been marked as paid.');
        }

        $request->validate([
            'payout_trx_id' => 'required|string|max:255'
        ]);

        $payout->update([
            'status' => 'paid',
            'payout_trx_id' => $request->payout_trx_id,
            'processed_at' => now(),
            'admin_notes' => 'Paid via ' . $payout->payout_method . ' on ' . now()->format('M d, Y') . '. TrxID: ' . $request->payout_trx_id
        ]);

        return back()->with('success', 'Payout for ' . $payout->instructor->name . ' has been marked as PAID with TrxID: ' . $request->payout_trx_id);
    }
}
