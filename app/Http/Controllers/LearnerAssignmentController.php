<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LearnerAssignmentController extends Controller
{
    public function submit(Request $request, Assignment $assignment)
    {
        $request->validate([
            'file' => 'required|file|max:20480', // 20MB limit
        ]);
        
        $user = Auth::user();

        // Check if already submitted
        $existing = AssignmentSubmission::where('assignment_id', $assignment->id)->where('user_id', $user->id)->first();
        if ($existing) {
             return back()->with('error', 'You have already submitted this assignment.');
        }
        
        $path = $request->file('file')->store('assignments', 'public');
        
        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'user_id' => $user->id,
            'file_path' => $path,
            'status' => 'pending'
        ]);
        
        // Mark lesson complete immediately upon submission
        $user->completedLessons()->syncWithoutDetaching([$assignment->lesson_id => ['completed_at' => now()]]);
        
        return back()->with('success', 'Assignment submitted successfully. It is now pending instructor review.');
    }
}
