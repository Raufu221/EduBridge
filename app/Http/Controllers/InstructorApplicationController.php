<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstructorApplication;

class InstructorApplicationController extends Controller
{
    public function create()
    {
        // If they are logged in and already an instructor or admin, they don't need to apply!
        if (auth()->check() && (auth()->user()->role === 'instructor' || auth()->user()->role === 'admin')) {
            return redirect()->route('dashboard')->with('success', 'You are already an instructor or admin!');
        }

        // Has the logged-in user already submitted a pending application?
        $existingApplication = null;
        if (auth()->check()) {
            $existingApplication = InstructorApplication::where('user_id', auth()->id())
                ->where('status', 'pending')
                ->first();
        }

        // Pass it to the view
        return view('pages.teach', compact('existingApplication'));
    }

    public function store(Request $request)
    {
        // 1. Basic Security & Role Checks
        if (auth()->check() && (auth()->user()->role === 'instructor' || auth()->user()->role === 'admin')) {
            return redirect()->route('dashboard');
        }

        // 2. Prevent Duplicate Applications (Guest or Auth)
        $email = auth()->check() ? auth()->user()->email : $request->email;
        if (InstructorApplication::where('email', $email)->where('status', 'pending')->exists()) {
            return back()->withInput()->withErrors(['email' => 'An application is already pending for this email address.']);
        }

        if (auth()->check() && InstructorApplication::where('user_id', auth()->id())->where('status', 'pending')->exists()) {
            return redirect()->route('teach.index')->with('error', 'You already have a pending application.');
        }

        // 3. Validation Logic (Guest vs Auth)
        $rules = [
            'expertise' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'proposal_topic' => 'required|string|max:255',
            'teaching_approach' => 'required|string|min:50',
            'demo_video_url' => 'nullable|url|max:255',
            'phone' => 'nullable|string|max:20',
            'linkedin' => 'nullable|url|max:255',
            'portfolio' => 'nullable|url|max:255',
        ];

        if (!auth()->check()) {
            $rules['full_name'] = 'required|string|max:255';
            $rules['email'] = 'required|email|max:255';
        }

        $request->validate($rules);

        // 4. Save the Application
        InstructorApplication::create([
            'user_id' => auth()->id(), // null for guests
            'full_name' => auth()->check() ? auth()->user()->name : $request->full_name,
            'email' => auth()->check() ? auth()->user()->email : $request->email,
            'phone' => $request->phone,
            'linkedin' => $request->linkedin,
            'expertise' => $request->expertise,
            'experience_years' => $request->experience_years,
            'portfolio' => $request->portfolio,
            'proposal_topic' => $request->proposal_topic,
            'teaching_approach' => $request->teaching_approach,
            'demo_video_url' => $request->demo_video_url,
            'status' => 'pending',
        ]);

        return redirect()->route('teach.success');
    }

    public function success()
    {
        return view('pages.teach-success');
    }
}
