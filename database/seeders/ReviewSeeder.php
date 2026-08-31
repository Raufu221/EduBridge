<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Course;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        $course = Course::where('is_published', true)->first();
        $student = User::where('role', 'student')->first();

        if ($course && $student) {
            Review::updateOrCreate(
                ['user_id' => $student->id, 'course_id' => $course->id],
                [
                    'rating' => 5,
                    'comment' => "This course completely changed my perspective on " . $course->category->name . ". The instructor was clear, and the projects were incredibly helpful for my career!",
                ]
            );
        }
    }
}
