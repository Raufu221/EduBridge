<?php
/*
|--------------------------------------------------------------------------
| SSLCommerz v4 API Integration
|--------------------------------------------------------------------------
*/

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SslCommerzPaymentController extends Controller
{
    use \App\Traits\HandlesEnrollment;

    private $store_id;
    private $store_password;
    private $api_url;
    private $validation_url;

    public function __construct()
    {
        $this->store_id = env('SSLCZ_STORE_ID');
        $this->store_password = env('SSLCZ_STORE_PASSWORD');
        $test_mode = env('SSLCZ_TESTMODE', true);

        if ($test_mode) {
            $this->api_url = "https://sandbox.sslcommerz.com/gwprocess/v4/api.php";
            $this->validation_url = "https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php";
        } else {
            $this->api_url = "https://securepay.sslcommerz.com/gwprocess/v4/api.php";
            $this->validation_url = "https://securepay.sslcommerz.com/validator/api/validationserverAPI.php";
        }
    }

    /**
     * Initiate payment session
     */
    public function pay(Request $request, Course $course)
    {
        $user = Auth::user();
        $tran_id = "SSLCZ_" . uniqid();

        // 1. Create Pending Transaction
        $gross = $course->price;
        $commission = $gross * 0.30;
        $instructor = $gross * 0.70;

        Transaction::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'gross_amount' => $gross,
            'net_paid' => $gross,
            'commission_amount' => $commission,
            'instructor_amount' => $instructor,
            'payment_method' => 'sslcommerz',
            'manual_trx_id' => $tran_id, // Store our internal ID here
            'status' => 'pending',
        ]);

        // 2. Prepare Payload
        $post_data = [
            'store_id' => $this->store_id,
            'store_passwd' => $this->store_password,
            'total_amount' => $gross,
            'currency' => 'BDT',
            'tran_id' => $tran_id,
            'success_url' => route('ssl.success'),
            'fail_url' => route('ssl.fail'),
            'cancel_url' => route('ssl.cancel'),
            'ipn_url' => route('ssl.ipn'),

            // Student Details
            'cus_name' => $user->name,
            'cus_email' => $user->email,
            'cus_add1' => 'Dhaka',
            'cus_city' => 'Dhaka',
            'cus_state' => 'Dhaka',
            'cus_postcode' => '1000',
            'cus_country' => 'Bangladesh',
            'cus_phone' => '01700000000',

            // Product Details
            'shipping_method' => 'NO',
            'product_name' => $course->title,
            'product_category' => 'Education',
            'product_profile' => 'non-physical-goods',
        ];

        // 3. Call SSLCommerz API
        try {
            $response = Http::asForm()->post($this->api_url, $post_data);
            $result = $response->json();

            if (isset($result['status']) && $result['status'] === 'SUCCESS') {
                return redirect()->away($result['GatewayPageURL']);
            }

            Log::error("SSLCommerz Session Error: " . ($result['failedreason'] ?? 'Unknown Error'));
            return redirect()->back()->with('error', 'Unable to initiate payment. Please try again.');

        } catch (\Exception $e) {
            Log::error("SSLCommerz Exception: " . $e->getMessage());
            return redirect()->back()->with('error', 'Connection error. Please try again.');
        }
    }

    /**
     * Handle Success Callback
     */
    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $val_id = $request->input('val_id');
        $amount = $request->input('amount');

        // Verify with SSLCommerz
        $response = Http::get($this->validation_url, [
            'val_id' => $val_id,
            'store_id' => $this->store_id,
            'store_passwd' => $this->store_password,
            'format' => 'json'
        ]);

        $result = $response->json() ?? [];
        
        Log::info("SSLCommerz Validation Response:", (array)$result);

        if (isset($result['status']) && ($result['status'] === 'VALID' || $result['status'] === 'VALIDATED')) {
            // Transaction is valid
            $transaction = Transaction::where('manual_trx_id', $tran_id)->first();

            if ($transaction) {
                // Always restore session
                Auth::login($transaction->user);

                // Ensure Enrollment & Transaction are processed
                $this->completeEnrollmentLogic(
                    $transaction->user_id,
                    $transaction->course_id,
                    $amount,
                    $tran_id,
                    'sslcommerz'
                );

                return redirect()->route('learner.checkout.success', $transaction->course_id)
                                 ->with('method', 'sslcommerz');
            }
        }

        return redirect()->route('home')->with('error', 'Payment validation failed.');
    }

    /**
     * Handle Fail Callback
     */
    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $transaction = Transaction::where('manual_trx_id', $tran_id)->first();

        if ($transaction) {
            // Restore Session
            Auth::login($transaction->user);
            
            $transaction->update(['status' => 'failed']);
            return redirect()->route('learner.checkout.show', $transaction->course_id)
                             ->with('error', 'Payment failed. Please try again.');
        }

        return redirect()->route('home');
    }

    /**
     * Handle Cancel Callback
     */
    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $transaction = Transaction::where('manual_trx_id', $tran_id)->first();

        if ($transaction) {
            // Restore Session
            Auth::login($transaction->user);

            $transaction->update(['status' => 'canceled']);
            return redirect()->route('learner.checkout.show', $transaction->course_id)
                             ->with('info', 'Payment canceled.');
        }

        return redirect()->route('home');
    }

    /**
     * Handle IPN Callback (Redundancy)
     */
    public function ipn(Request $request)
    {
        // Simple logic for IPN
        if ($request->input('status') === 'VALID') {
            $this->success($request);
        }
    }
}
