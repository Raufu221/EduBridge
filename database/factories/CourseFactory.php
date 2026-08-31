<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(rand(3, 8));
        return [
            'title' => rtrim($title, '.'),
            'slug' => Str::slug($title),
            'description' => fake()->paragraphs(3, true),
            'category_id' => Category::inRandomOrder()->first()->id ?? 1,
            'instructor_id' => User::where('role', 'instructor')->inRandomOrder()->first()->id ?? 1,
            'price' => fake()->randomElement([0, 19.99, 49.99, 99.99]),
            // Generate a random gradient for the cover image
            'cover_image' => 'https://ui-avatars.com/api/?name=' . urlencode(rtrim($title, '.')) . '&background=random&color=fff&size=800&font-size=0.33',
            'is_published' => true,
            'is_submitted' => true,
        ];
    }
}
