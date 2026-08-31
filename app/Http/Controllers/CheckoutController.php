<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Transaction;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController extends Controller
{
    use \App\Traits\HandlesEnrollment;

    /**
     * Show the checkout page.
     */
    public function show(Course $course)
    {
        $user = Auth::user();

        // Already enrolled?
        if ($user->enrollments()->where('course_id', $course->id)->exists()) {
            return redirect()->route('learner.course.viewer', $course)->with('info', 'You are already enrolled!');
        }

        $pending = Transaction::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'pending')
            ->exists();

        return view('learner.checkout', compact('course', 'pending'));
    }

    /**
     * Process Card Payment (Stripe).
     */
    public function processStripe(Request $request, Course $course)
    {
        // --- RULE: FREE COURSE BYPASS ---
        if ($course->price <= 0) {
            $this->completeEnrollmentLogic(Auth::id(), $course->id, 0, 'FREE-' . uniqid(), 'free');
            return redirect()->route('learner.dashboard')->with('success', 'Successfully enrolled in the free course!');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'bdt',
                    'product_data' => [
                        'name' => $course->title,
                        'description' => 'Course Enrollment Fee',
                    ],
                    'unit_amount' => $course->price * 100, // Stripe uses cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('learner.checkout.success', $course->id) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('learner.checkout.show', $course->id),
            'metadata' => [
                'user_id' => Auth::id(),
                'course_id' => $course->id,
            ],
        ]);

        return redirect()->away($checkout_session->url);
    }

    /**
     * Success landing page for Stripe.
     * VERIFIES SESSION INSTANTLY FOR LOCALHOST DEFENSE.
     */
    public function success(Request $request, Course $course)
    {
        $sessionId = $request->get('session_id');
        $method = $request->get('method') ?? session('method') ?? 'stripe';

        if ($sessionId) {
            try {
                Stripe::setApiKey(config('services.stripe.secret'));
                $session = Session::retrieve($sessionId);

                if ($session->payment_status === 'paid') {
                    // Use shared logic for instant enrollment
                    $this->completeEnrollmentLogic(
                        $session->metadata->user_id,
                        $session->metadata->course_id,
                        $session->amount_total / 100,
                        $session->id,
                        'stripe'
                    );
                    $method = 'stripe';
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Direct success verification failed: " . $e->getMessage());
            }
        }

        return view('learner.checkout-success', [
            'course' => $course,
            'method' => $method
        ]);
    }

}
