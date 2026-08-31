<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'progress_percent',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($enrollment) {
            if ($enrollment->status === 'active') {
                \App\Models\Waitlist::where('user_id', $enrollment->user_id)
                    ->where('course_id', $enrollment->course_id)
                    ->delete();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Calculate the cumulative average score for this enrollment.
     */
    public function calculateAverage()
    {
        $courseId = $this->course_id;
        $userId = $this->user_id;

        $quizIds = \App\Models\Quiz::whereHas('lesson.module', function($q) use ($courseId) {
            $q->where('course_id', $courseId);
        })->pluck('id');

        $assignmentIds = \App\Models\Assignment::whereHas('lesson.module', function($q) use ($courseId) {
            $q->where('course_id', $courseId);
        })->pluck('id');

        $scores = [];

        foreach ($quizIds as $quizId) {
            $bestAttempt = \App\Models\QuizAttempt::where('quiz_id', $quizId)
                ->where('user_id', $userId)
                ->orderByDesc('score')
                ->first();
            $scores[] = $bestAttempt ? ($bestAttempt->score / $bestAttempt->total_points) * 100 : 0;
        }

        foreach ($assignmentIds as $assignmentId) {
            $submission = \App\Models\AssignmentSubmission::where('assignment_id', $assignmentId)
                ->where('user_id', $userId)
                ->where('status', 'graded')
                ->first();
            if ($submission) {
                $assignment = \App\Models\Assignment::find($assignmentId);
                $scores[] = ($submission->marks_awarded / $assignment->total_marks) * 100;
            } else {
                $scores[] = 0;
            }
        }

        $totalAssessments = count($scores);
        return $totalAssessments > 0 ? array_sum($scores) / $totalAssessments : 100;
    }

    public function isCertificateEligible()
    {
        if ($this->progress_percent < 100) return false;
        
        // Count total quizzes/assignments. If 0, automatically eligible at 100% progress.
        $assessmentsCount = \App\Models\Quiz::whereHas('lesson.module', function($q) {
            $q->where('course_id', $this->course_id);
        })->count() + \App\Models\Assignment::whereHas('lesson.module', function($q) {
            $q->where('course_id', $this->course_id);
        })->count();

        if ($assessmentsCount === 0) return true;

        return $this->calculateAverage() >= 80;
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class, 'course_id', 'course_id')
                    ->where('user_id', $this->user_id);
    }
}
