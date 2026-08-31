<?php

namespace App\Traits;

use App\Models\Transaction;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Log;

trait HandlesEnrollment
{
    /**
     * Consolidates the logic for creating a transaction and granting access.
     * This ensures the 30/70 split is identical everywhere.
     */
    protected function completeEnrollmentLogic($userId, $courseId, $amount, $gatewayRef = null, $method = 'stripe')
    {
        // 1. Math for the split (30% platform, 70% instructor)
        $commission = $amount * 0.30;
        $instructorAmount = $amount * 0.70;

        // 2. Find and Update or Create Transaction
        $transaction = Transaction::where('gateway_ref', $gatewayRef)
                    ->orWhere('manual_trx_id', $gatewayRef)
                    ->first();

        if ($transaction) {
            $transaction->update([
                'status' => 'completed',
                'gateway_ref' => $gatewayRef, 
                'net_paid' => $amount,
                'clearance_date' => now()->addDays(14),
            ]);
        } else {
            Transaction::create([
                'user_id' => $userId,
                'course_id' => $courseId,
                'gross_amount' => $amount,
                'net_paid' => $amount,
                'commission_amount' => $commission,
                'instructor_amount' => $instructorAmount,
                'payment_method' => $method,
                'gateway_ref' => $gatewayRef,
                'status' => 'completed',
                'clearance_date' => now()->addDays(14),
            ]);
        }

        // 3. Grant Course Access (Always do this to be safe)
        Enrollment::updateOrCreate(
            ['user_id' => $userId, 'course_id' => $courseId],
            ['status' => 'active']
        );

        Log::info("Enrollment verified for User $userId in Course $courseId via $method.");
    }
}
