<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Certificate;
use App\Models\Enrollment;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    /**
     * Check if a student is eligible to claim a certificate.
     */
    public function checkEligibility(Course $course)
    {
        $user = Auth::user();
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return ['eligible' => false, 'reason' => 'You are not enrolled in this course.'];
        }

        if (!$enrollment->isCertificateEligible()) {
            $avg = $enrollment->calculateAverage();
            $reason = $enrollment->progress_percent < 100 
                ? 'You must complete 100% of the lessons first.' 
                : 'Your average score is ' . round($avg, 1) . '%. You need at least 80% to earn a certificate.';
            
            return [
                'eligible' => false, 
                'reason' => $reason,
                'average' => $avg,
                'progress' => $enrollment->progress_percent
            ];
        }

        return [
            'eligible' => true, 
            'average' => $enrollment->calculateAverage(),
            'existing' => Certificate::where('user_id', $user->id)->where('course_id', $course->id)->first()
        ];
    }

    /**
     * Store a claimed certificate.
     */
    public function claim(Request $request, Course $course)
    {
        $eligibility = $this->checkEligibility($course);
        
        if (!$eligibility['eligible']) {
            return back()->with('error', $eligibility['reason']);
        }

        if ($eligibility['existing']) {
            return back()->with('info', 'You have already claimed your certificate for this course.');
        }

        $request->validate([
            'full_name' => 'required|string|max:255|min:3',
        ]);

        $certificate = Certificate::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'certificate_code' => $this->generateUniqueCode(),
            'full_name' => $request->full_name,
            'average_score' => $eligibility['average'],
            'issue_date' => now(),
        ]);

        return back()->with('success', 'Congratulations! Your certificate has been issued.');
    }

    /**
     * Download the certificate PDF.
     */
    public function download(Certificate $certificate)
    {
        if ($certificate->user_id !== Auth::id()) abort(403);
        if (!$certificate->is_valid) return back()->with('error', 'This certificate has been revoked.');

        $course = $certificate->course;
        $instructor = $course->instructor;
        $verificationUrl = route('certificate.verify', $certificate->certificate_code);

        // Generate QR Code (SVG)
        $qrCode = base64_encode(QrCode::format('svg')->size(100)->generate($verificationUrl));

        $pdf = Pdf::loadView('pdf.certificate', [
            'certificate' => $certificate,
            'course' => $course,
            'instructor' => $instructor,
            'qrCode' => $qrCode
        ])->setPaper('a4', 'landscape');

        return $pdf->download("Certificate_{$certificate->certificate_code}.pdf");
    }

    /**
     * Preview the certificate in browser.
     */
    public function preview(Certificate $certificate)
    {
        if ($certificate->user_id !== Auth::id()) abort(403);
        
        $course = $certificate->course;
        $instructor = $course->instructor;
        $verificationUrl = route('certificate.verify', $certificate->certificate_code);

        // Generate QR Code (SVG)
        $qrCode = base64_encode(QrCode::format('svg')->size(100)->generate($verificationUrl));

        return view('pdf.certificate', [
            'certificate' => $certificate,
            'course' => $course,
            'instructor' => $instructor,
            'qrCode' => $qrCode
        ]);
    }

    /**
     * Public verification page.
     */
    public function verify($code)
    {
        $certificate = Certificate::where('certificate_code', $code)->with(['user', 'course.instructor'])->first();
        return view('pages.verify-certificate', compact('certificate'));
    }

    private function generateUniqueCode()
    {
        do {
            $code = 'EDU-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
        } while (Certificate::where('certificate_code', $code)->exists());
        return $code;
    }
}
