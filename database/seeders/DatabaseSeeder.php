<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category; // <--- ADD THIS LINE RIGHT HERE!
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <-- ADDED THIS! Very important for passwords.

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        // 1. The Super Admin Account
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@edubridge.com', 
            'password' => Hash::make('password123'), 
            'role' => 'admin',
        ]);

        // 2. The Instructor Account
        User::create([
            'name' => 'Teacher John',
            'email' => 'teacher@edubridge.com',
            'password' => Hash::make('password123'),
            'role' => 'instructor',
            'about_me' => 'Expert web developer and instructor.',
        ]);

        // 3. The Student Account (Using Laravel's factory, defaults to 'student' role)
        User::factory()->create([
            'name' => 'Test Student',
            'email' => 'student@example.com',
            // The factory automatically generates a password of 'password'
        ]);
        // 4. Create standard Course Categories
        $categories = [
            ['name' => 'Web Development', 'slug' => 'web-development'],
            ['name' => 'Data Science', 'slug' => 'data-science'],
            ['name' => 'Machine Learning & AI', 'slug' => 'machine-learning-and-ai'],
            ['name' => 'Mobile App Development', 'slug' => 'mobile-app-development'],
            ['name' => 'Software Engineering', 'slug' => 'software-engineering'],
            ['name' => 'Cyber Security', 'slug' => 'cyber-security'],
            ['name' => 'Cloud Computing', 'slug' => 'cloud-computing'],
            ['name' => 'Graphic Design', 'slug' => 'graphic-design'],
            ['name' => '3D & Animation', 'slug' => '3d-and-animation'],
            ['name' => 'Business & Marketing', 'slug' => 'business-marketing'],
            ['name' => 'Finance & Accounting', 'slug' => 'finance-and-accounting'],
            ['name' => 'Photography & Video', 'slug' => 'photography-and-video'],
            ['name' => 'Personal Development', 'slug' => 'personal-development'],
            ['name' => 'IT Certifications', 'slug' => 'it-certifications'],
            ['name' => 'Language Learning', 'slug' => 'language-learning'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // --- BULK PRACTICUM DATA GENERATION ---
        $this->call([
            PracticumSeeder::class
        ]);
    }
}