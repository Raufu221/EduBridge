<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\AdminController;

Route::get('/', [\App\Http\Controllers\PublicController::class, 'home'])->name('home');

Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'admin') return redirect()->route('admin.dashboard');
    if ($role === 'instructor') return redirect()->route('instructor.dashboard');
    
    return redirect()->route('learner.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Public Course Catalog & Sales Pages
Route::get('/courses', [\App\Http\Controllers\PublicController::class, 'courses'])->name('courses.index');
Route::get('/courses/{course}', [\App\Http\Controllers\PublicController::class, 'show'])->name('courses.show');
Route::get('/instructors', [\App\Http\Controllers\PublicController::class, 'instructorsIndex'])->name('instructors.index');
Route::get('/instructors/{user}', [\App\Http\Controllers\PublicController::class, 'instructorProfile'])->name('instructor.profile');
Route::get('/reviews', [\App\Http\Controllers\PublicController::class, 'reviewsIndex'])->name('reviews.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

// --- PUBLIC TEACH WITH US ROUTES ---
Route::get('/teach-with-us', [\App\Http\Controllers\InstructorApplicationController::class, 'create'])->name('teach.index');
Route::post('/teach-with-us', [\App\Http\Controllers\InstructorApplicationController::class, 'store'])->name('teach.store');
Route::get('/teach-with-us/success', [\App\Http\Controllers\InstructorApplicationController::class, 'success'])->name('teach.success');
// --- ADMIN ROUTES ---
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/courses/approvals', [AdminController::class, 'courseApprovals'])->name('admin.courses.approvals');
    Route::post('/admin/courses/{course}/approve', [AdminController::class, 'approveCourse'])->name('admin.courses.approve');
    Route::post('/admin/courses/{course}/reject', [AdminController::class, 'rejectCourse'])->name('admin.courses.reject');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users.index');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::put('/admin/users/{user}/promote', [AdminController::class, 'promoteUser'])->name('admin.users.promote');
    Route::put('/admin/users/{user}/suspend', [AdminController::class, 'suspendUser'])->name('admin.users.suspend');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    
    // Instructor Applications Queue
    Route::get('/admin/applications', [AdminController::class, 'applications'])->name('admin.applications.index');
    Route::post('/admin/applications/{application}/approve', [AdminController::class, 'approveApplication'])->name('admin.applications.approve');
    Route::post('/admin/applications/{application}/reject', [AdminController::class, 'rejectApplication'])->name('admin.applications.reject');
    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories.index');
    Route::post('/admin/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::put('/admin/categories/{category}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [AdminController::class, 'destroyCategory'])->name('admin.categories.destroy');
    
    // Category Drilldown & Admin Global Preview
    Route::get('/admin/categories/{category}', [AdminController::class, 'showCategory'])->name('admin.categories.show');
    Route::get('/admin/course/{course}/preview', [AdminController::class, 'previewCourse'])->name('admin.course.preview');

    // Certificates Management
    Route::get('/admin/certificates', [AdminController::class, 'certificates'])->name('admin.certificates.index');
    Route::patch('/admin/certificates/{certificate}/toggle', [AdminController::class, 'toggleCertificateValidity'])->name('admin.certificates.toggle');
    
    // Reviews Moderation & Management
    Route::get('/admin/reviews', [AdminController::class, 'reviews'])->name('admin.reviews.index');
    Route::post('/admin/reviews/{review}/toggle', [AdminController::class, 'toggleReviewVisibility'])->name('admin.reviews.toggle');
    Route::delete('/admin/reviews/{review}', [AdminController::class, 'destroyReview'])->name('admin.reviews.delete');

    // Payment & Revenue Moderation (Phase 3)
    Route::get('/admin/revenue', [AdminController::class, 'revenueAnalytics'])->name('admin.revenue.index');
    Route::get('/admin/payments', [AdminController::class, 'payments'])->name('admin.payments.index');
    Route::post('/admin/payments/{transaction}/approve', [AdminController::class, 'approvePayment'])->name('admin.payments.approve');
    Route::post('/admin/payments/{transaction}/reject', [AdminController::class, 'rejectPayment'])->name('admin.payments.reject');
    Route::post('/admin/payments/{transaction}/simulate', [AdminController::class, 'simulateSuccess'])->name('admin.payments.simulate');

    // Payout Management (Monetization Final)
    Route::get('/admin/payouts', [AdminController::class, 'payouts'])->name('admin.payouts.index');
    Route::post('/admin/payouts/{payout}/paid', [AdminController::class, 'markPayoutPaid'])->name('admin.payouts.paid');

    // Global Announcements
    Route::get('/admin/announcements/create', [\App\Http\Controllers\AdminAnnouncementController::class, 'create'])->name('admin.announcements.create');
    Route::post('/admin/announcements', [\App\Http\Controllers\AdminAnnouncementController::class, 'store'])->name('admin.announcements.store');
});



// Global Notifications (Accessible to All Roles)
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
});

// --- INSTRUCTOR ROUTES ---
Route::middleware(['auth', 'role:instructor'])->group(function () {
    // Dashboard, Analytics & Earnings
    Route::get('/instructor/dashboard', [InstructorController::class, 'dashboard'])->name('instructor.dashboard');
    Route::get('/instructor/analytics', [InstructorController::class, 'analytics'])->name('instructor.analytics');
    Route::get('/instructor/earnings', [InstructorController::class, 'earnings'])->name('instructor.earnings');
    
    
    // Students Roster
    Route::get('/instructor/students', [InstructorController::class, 'students'])->name('instructor.students.index');
    Route::delete('/instructor/students/{enrollment}/revoke', [InstructorController::class, 'revokeAccess'])->name('instructor.students.revoke');
    
    // All Courses Page (THIS IS THE ONE LARAVEL COULDN'T FIND!)
    Route::get('/instructor/courses', [InstructorController::class, 'index'])->name('instructor.courses.index');
    
    // Create New Course Form (GET shows the form, POST saves the data)
    Route::get('/instructor/course/create', [InstructorController::class, 'create'])->name('instructor.course.create');
    Route::post('/instructor/course/create', [InstructorController::class, 'store'])->name('instructor.course.store');

    // Tabbed Course Builder Content Update
    Route::put('/instructor/course/{course}', [InstructorController::class, 'update'])->name('instructor.course.update');
    
    // Deletion Rules Flow
    Route::delete('/instructor/course/{course}', [InstructorController::class, 'destroy'])->name('instructor.course.destroy');
    Route::put('/instructor/course/{course}/unpublish', [InstructorController::class, 'unpublish'])->name('instructor.course.unpublish');
    
   // Course Curriculum Builder (Notice the {course} parameter!)
    Route::get('/instructor/course/{course}/builder', [InstructorController::class, 'builder'])->name('instructor.course.builder');
    // Save a new Module
    Route::post('/instructor/course/{course}/module', [InstructorController::class, 'storeModule'])->name('instructor.module.store');
    
    // Save a new Lesson inside a specific Module
    Route::post('/instructor/course/{course}/module/{module}/lesson', [InstructorController::class, 'storeLesson'])->name('instructor.lesson.store');

    // Submit for Approval & Preview
    Route::post('/instructor/course/{course}/submit', [InstructorController::class, 'submit'])->name('instructor.course.submit');
    Route::get('/instructor/course/{course}/preview', [InstructorController::class, 'preview'])->name('instructor.course.preview');

    // Edit/Delete Modules
    Route::put('/instructor/course/{course}/module/{module}', [InstructorController::class, 'updateModule'])->name('instructor.module.update');
    Route::delete('/instructor/course/{course}/module/{module}', [InstructorController::class, 'destroyModule'])->name('instructor.module.destroy');
    
    // Edit/Delete Lessons
    Route::put('/instructor/course/{course}/module/{module}/lesson/{lesson}', [InstructorController::class, 'updateLesson'])->name('instructor.lesson.update');
    Route::delete('/instructor/course/{course}/module/{module}/lesson/{lesson}', [InstructorController::class, 'destroyLesson'])->name('instructor.lesson.destroy');

    // Quiz Builder
    Route::get('/instructor/course/{course}/quiz/{lesson}/build', [InstructorController::class, 'buildQuiz'])->name('instructor.quiz.build');
    Route::post('/instructor/course/{course}/quiz/{lesson}/question', [InstructorController::class, 'storeQuestion'])->name('instructor.quiz.question.store');
    Route::get('/instructor/course/{course}/quiz/{lesson}/question/{question}/edit', [InstructorController::class, 'editQuestion'])->name('instructor.quiz.question.edit');
    Route::put('/instructor/course/{course}/quiz/{lesson}/question/{question}', [InstructorController::class, 'updateQuestion'])->name('instructor.quiz.question.update');
    Route::delete('/instructor/course/{course}/quiz/{lesson}/question/{question}', [InstructorController::class, 'destroyQuestion'])->name('instructor.quiz.question.destroy');

    // Global Assessments Dashboards
    Route::get('/instructor/assessments/quizzes', [InstructorController::class, 'quizzes'])->name('instructor.assessments.quizzes');
    Route::get('/instructor/assessments/assignments', [InstructorController::class, 'assignments'])->name('instructor.assessments.assignments');
    Route::get('/instructor/assessments/grading', [InstructorController::class, 'grading'])->name('instructor.assessments.grading');
    Route::post('/instructor/assessments/grading/{submission}', [InstructorController::class, 'storeGrade'])->name('instructor.assessments.grading.store');

    // Feedback & Reputation Management
    Route::get('/instructor/reviews', [InstructorController::class, 'reviews'])->name('instructor.reviews.index');
    Route::post('/instructor/reviews/{review}/reply', [InstructorController::class, 'replyToReview'])->name('instructor.reviews.reply');

    // Announcements
    Route::get('/instructor/announcements', [\App\Http\Controllers\AnnouncementController::class, 'index'])->name('instructor.announcements.index');
    Route::post('/instructor/announcements', [\App\Http\Controllers\AnnouncementController::class, 'store'])->name('instructor.announcements.store');

    // Payout System
    Route::post('/instructor/payout/request', [InstructorController::class, 'storePayoutRequest'])->name('instructor.payout.store');

    // Settings & Profile
    Route::get('/instructor/settings', [\App\Http\Controllers\InstructorSettingsController::class, 'show'])->name('instructor.settings');
    Route::post('/instructor/settings/profile', [\App\Http\Controllers\InstructorSettingsController::class, 'updateProfile'])->name('instructor.settings.profile');
    Route::post('/instructor/settings/password', [\App\Http\Controllers\InstructorSettingsController::class, 'updatePassword'])->name('instructor.settings.password');
    Route::post('/instructor/settings/notifications', [\App\Http\Controllers\InstructorSettingsController::class, 'updateNotifications'])->name('instructor.settings.notifications');
});

// Shared Notification Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/clear', [\App\Http\Controllers\NotificationController::class, 'clearAll'])->name('notifications.clear');
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
});

// --- LEARNER ROUTES ---
Route::middleware(['auth'])->prefix('learner')->name('learner.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\LearnerController::class, 'dashboard'])->name('dashboard');
    
    // Monetization & Checkout
    Route::get('/course/{course}/checkout', [\App\Http\Controllers\CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/course/{course}/checkout/stripe', [\App\Http\Controllers\CheckoutController::class, 'processStripe'])->name('checkout.stripe');
    Route::post('/course/{course}/checkout/sslcommerz', [\App\Http\Controllers\SslCommerzPaymentController::class, 'pay'])->name('checkout.sslcommerz');
    Route::get('/course/{course}/checkout/success', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');

    Route::post('/course/{course}/enroll', [\App\Http\Controllers\EnrollmentController::class, 'store'])->name('course.enroll');
    Route::get('/course/{course}/announcements/{announcement}', [\App\Http\Controllers\AnnouncementController::class, 'showLearner'])->name('course.announcement.show');
    Route::get('/course/{course}/learn/{lesson?}', [\App\Http\Controllers\CourseViewerController::class, 'show'])->name('course.viewer');
    Route::post('/lesson/{lesson}/complete', [\App\Http\Controllers\CourseViewerController::class, 'markComplete'])->name('lesson.complete');
    
    // Submissions
    Route::post('/quiz/{quiz}/submit', [\App\Http\Controllers\LearnerQuizController::class, 'submit'])->name('quiz.submit');
    Route::post('/assignment/{assignment}/submit', [\App\Http\Controllers\LearnerAssignmentController::class, 'submit'])->name('assignment.submit');
    
    // Waitlist
    Route::post('/course/{course}/waitlist', [\App\Http\Controllers\WaitlistController::class, 'store'])->name('course.waitlist');
    Route::delete('/course/{course}/waitlist', [\App\Http\Controllers\WaitlistController::class, 'destroy'])->name('course.waitlist.destroy');

    // Reviews
    Route::post('/course/{course}/review', [\App\Http\Controllers\ReviewController::class, 'store'])->name('course.review');

    Route::post('/course/{course}/certificate/claim', [\App\Http\Controllers\CertificateController::class, 'claim'])->name('certificate.claim');
    Route::get('/certificate/{certificate}/download', [\App\Http\Controllers\CertificateController::class, 'download'])->name('certificate.download');
    Route::get('/certificate/{certificate}/preview', [\App\Http\Controllers\CertificateController::class, 'preview'])->name('certificate.preview');

    // AI Tutor
    Route::post('/ai/ask', [\App\Http\Controllers\AiTutorController::class, 'askQuestion'])->name('ai.ask');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\LearnerSettingsController::class, 'show'])->name('settings');
    Route::post('/settings/profile', [\App\Http\Controllers\LearnerSettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/password', [\App\Http\Controllers\LearnerSettingsController::class, 'updatePassword'])->name('settings.password');
});

// Public Certificate Verification (Unauthenticated)
Route::get('/verify-certificate/{code}', [\App\Http\Controllers\CertificateController::class, 'verify'])->name('certificate.verify');

// Stripe Webhook (CSRF Excluded in bootstrap/app.php)
Route::post('/webhook/stripe', [\App\Http\Controllers\StripeWebhookController::class, 'handle'])->name('webhook.stripe');

// SSLCommerz Callback Routes (CSRF Excluded in bootstrap/app.php)
Route::post('/ssl/success', [\App\Http\Controllers\SslCommerzPaymentController::class, 'success'])->name('ssl.success');
Route::post('/ssl/fail', [\App\Http\Controllers\SslCommerzPaymentController::class, 'fail'])->name('ssl.fail');
Route::post('/ssl/cancel', [\App\Http\Controllers\SslCommerzPaymentController::class, 'cancel'])->name('ssl.cancel');
Route::post('/ssl/ipn', [\App\Http\Controllers\SslCommerzPaymentController::class, 'ipn'])->name('ssl.ipn');

require __DIR__.'/auth.php';
