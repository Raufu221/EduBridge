<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LearnerQuizController extends Controller
{
    public function submit(Request $request, Quiz $quiz)
    {
        $user = Auth::user();
        
        $questions = $quiz->questions()->with('options')->get();
        $totalPoints = $questions->sum('points');
        $score = 0;
        
        foreach($questions as $question) {
            $selectedOptionId = $request->input('question_' . $question->id);
            if ($selectedOptionId) {
                // Find option
                $option = $question->options->firstWhere('id', $selectedOptionId);
                if ($option && $option->is_correct) {
                    $score += $question->points;
                }
            }
        }
        
        $percent = $totalPoints > 0 ? ($score / $totalPoints) * 100 : 0;
        $passed = $percent >= $quiz->passing_percent;
        
        QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            'score' => $score,
            'total_points' => $totalPoints,
            'passed' => $passed
        ]);
        
        if ($passed) {
            // Mark lesson complete
            $user->completedLessons()->syncWithoutDetaching([$quiz->lesson_id => ['completed_at' => now()]]);
        }
        
        return back()->with($passed ? 'success' : 'error', "You scored {$score}/{$totalPoints} (" . round($percent) . "%). " . ($passed ? 'You passed!' : 'You failed. Please try again.'));
    }
}
