<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\AnswerOption;
use App\Models\Assignment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PracticumSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Real-looking Instructors
        $instructorsData = [
            ['name' => 'Dr. Alan Turing', 'email' => 'alan@edubridge.com', 'about' => 'Former Professor of Computer Science at MIT with 20 years of experience in AI.'],
            ['name' => 'Sarah Drasner', 'email' => 'sarah@edubridge.com', 'about' => 'VP of Developer Experience. Core team member of Vue.js and CSS expert.'],
            ['name' => 'Wes Bos', 'email' => 'wes@edubridge.com', 'about' => 'Full Stack Developer, Speaker, and Teacher from Canada. Passionate about JavaScript.'],
            ['name' => 'Dr. Andrew Ng', 'email' => 'andrew@edubridge.com', 'about' => 'Founder of DeepLearning.AI. Former head of Google Brain.'],
            ['name' => 'Maximilian Schwarzmüller', 'email' => 'max@edubridge.com', 'about' => 'Professional Web Developer and Instructor. Creator of the React - The Complete Guide.'],
            ['name' => 'Angela Yu', 'email' => 'angela@edubridge.com', 'about' => 'Developer and Lead Instructor at the App Brewery, London\'s leading programming bootcamp.'],
            ['name' => 'Colt Steele', 'email' => 'colt@edubridge.com', 'about' => 'Developer and Bootcamp Instructor. Helped thousands of students learn to code.']
        ];

        $instructors = [];
        foreach ($instructorsData as $data) {
            $instructors[] = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'instructor',
                    'about_me' => $data['about']
                ]
            );
        }

        // 2. Curated Professional Courses
        $coursesData = [
            [
                'title' => 'The Complete React 18 Web Developer Course',
                'category_slug' => 'web-development',
                'description' => 'Learn how to build and launch React web applications using React, Redux, Webpack, React-Router, and more! This is a complete guide to modern React.',
                'price' => 89.99,
                'modules' => [
                    [
                        'title' => 'Getting Started with React',
                        'lessons' => [
                            ['title' => 'What is React?', 'type' => 'video'],
                            ['title' => 'Setting up the Environment (Vite + React)', 'type' => 'article'],
                            ['title' => 'Understanding JSX', 'type' => 'video'],
                        ]
                    ],
                    [
                        'title' => 'State & Props Deep Dive',
                        'lessons' => [
                            ['title' => 'The useState Hook', 'type' => 'video'],
                            ['title' => 'Passing Data with Props', 'type' => 'video'],
                            ['title' => 'React Fundamentals Quiz', 'type' => 'quiz', 
                                'quiz' => [
                                    ['q' => 'What hook is used to manage local state?', 'opts' => ['useContext', 'useEffect', 'useState', 'useReducer'], 'ans' => 'useState'],
                                    ['q' => 'Can props be modified by the child component?', 'opts' => ['Yes', 'No', 'Only if passed as an array'], 'ans' => 'No']
                                ]
                            ]
                        ]
                    ],
                    [
                        'title' => 'Final Project',
                        'lessons' => [
                            ['title' => 'Build a weather dashboard assignment', 'type' => 'assignment', 'marks' => 100]
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Machine Learning A-Z: Python & Python in Data Science',
                'category_slug' => 'machine-learning-and-ai',
                'description' => 'Learn Data Science, Machine Learning, and Artificial Intelligence using Python. Master algorithms like Random Forest, SVM, and Neural Networks.',
                'price' => 129.99,
                'modules' => [
                    [
                        'title' => 'Data Preprocessing',
                        'lessons' => [
                            ['title' => 'Handling Missing Data', 'type' => 'video'],
                            ['title' => 'Encoding Categorical Data', 'type' => 'article'],
                        ]
                    ],
                    [
                        'title' => 'Regression Networks',
                        'lessons' => [
                            ['title' => 'Simple Linear Regression', 'type' => 'video'],
                            ['title' => 'Multiple Linear Regression', 'type' => 'video'],
                            ['title' => 'Regression Concepts Quiz', 'type' => 'quiz', 
                                'quiz' => [
                                    ['q' => 'Which algorithm predicts a continuous value?', 'opts' => ['Classification', 'Regression', 'Clustering'], 'ans' => 'Regression'],
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Advanced CSS and Sass: Flexbox, Grid, Animations',
                'category_slug' => 'web-development',
                'description' => 'The most advanced and modern CSS course on the internet: master flexbox, CSS Grid, responsive design, and so much more.',
                'price' => 49.99,
                'modules' => [
                    [
                        'title' => 'CSS Architecture',
                        'lessons' => [
                            ['title' => 'BEM Methodology', 'type' => 'video'],
                            ['title' => 'Setting up Variables', 'type' => 'article'],
                        ]
                    ],
                    [
                        'title' => 'Layout Systems',
                        'lessons' => [
                            ['title' => 'Flexbox vs Grid', 'type' => 'video'],
                            ['title' => 'Build a Grid Layout Project', 'type' => 'assignment', 'marks' => 50]
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Flutter & Dart - The Complete Guide',
                'category_slug' => 'mobile-app-development',
                'description' => 'A complete guide to the Flutter SDK & Flutter Framework for building native iOS and Android apps.',
                'price' => 99.99,
                'modules' => [
                    [
                        'title' => 'Dart Fundamentals',
                        'lessons' => [
                            ['title' => 'Variables and Types', 'type' => 'video'],
                            ['title' => 'Object Oriented Dart', 'type' => 'video'],
                            ['title' => 'Dart Quiz', 'type' => 'quiz',
                                'quiz' => [
                                    ['q' => 'Does Dart support multiple inheritance?', 'opts' => ['Yes', 'No, but it has mixins'], 'ans' => 'No, but it has mixins']
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            [
                'title' => 'The Complete Cyber Security Course',
                'category_slug' => 'cyber-security',
                'description' => 'Learn Ethical Hacking, Penetration Testing, and Network Security in this comprehensive certification prep.',
                'price' => 149.99,
                'modules' => [
                    [
                        'title' => 'Networking Basics',
                        'lessons' => [
                            ['title' => 'The OSI Model', 'type' => 'video'],
                            ['title' => 'TCP/IP Protcols', 'type' => 'article'],
                        ]
                    ]
                ]
            ]
        ];

        // Ensure categories exist
        $categoriesArr = ['web-development', 'machine-learning-and-ai', 'mobile-app-development', 'cyber-security'];
        foreach($categoriesArr as $cat) {
            Category::firstOrCreate(['slug' => $cat], ['name' => ucwords(str_replace('-', ' ', $cat))]);
        }

        foreach ($coursesData as $courseData) {
            // Assign a random sophisticated instructor
            $randomInstructor = $instructors[array_rand($instructors)];
            $category = Category::where('slug', $courseData['category_slug'])->first();

            $course = Course::create([
                'title' => $courseData['title'],
                'slug' => Str::slug($courseData['title']),
                'category_id' => $category->id ?? 1,
                'instructor_id' => $randomInstructor->id,
                'description' => $courseData['description'],
                'price' => $courseData['price'],
                'is_published' => true,
                'is_submitted' => true,
                // Professional dummy cover image
                'cover_image' => 'https://ui-avatars.com/api/?name=' . urlencode($courseData['title']) . '&background=random&color=fff&size=800&font-size=0.33',
            ]);

            foreach ($courseData['modules'] as $mIndex => $modData) {
                $module = Module::create([
                    'course_id' => $course->id,
                    'title' => $modData['title'],
                    'order' => $mIndex + 1
                ]);

                foreach ($modData['lessons'] as $lIndex => $lesData) {
                    $lesson = Lesson::create([
                        'module_id' => $module->id,
                        'title' => $lesData['title'],
                        'type' => $lesData['type'],
                        'order' => $lIndex + 1,
                        'content' => 'In this lesson, you will learn about ' . $lesData['title'] . '.',
                        'video_url' => $lesData['type'] === 'video' ? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' : null,
                        'duration' => rand(5, 20)
                    ]);

                    // Generate Quiz if type is quiz
                    if ($lesData['type'] === 'quiz' && isset($lesData['quiz'])) {
                        $quiz = Quiz::create([
                            'lesson_id' => $lesson->id,
                            'time_limit_minutes' => 30,
                            'passing_percent' => 70
                        ]);

                        foreach ($lesData['quiz'] as $qData) {
                            $question = Question::create([
                                'quiz_id' => $quiz->id,
                                'question_text' => $qData['q'],
                                'points' => 10,
                                'rationale' => 'Review the previous material.'
                            ]);

                            foreach ($qData['opts'] as $opt) {
                                AnswerOption::create([
                                    'question_id' => $question->id,
                                    'option_text' => $opt,
                                    'is_correct' => ($opt === $qData['ans'])
                                ]);
                            }
                        }
                    }

                    // Generate Assignment if type is assignment
                    if ($lesData['type'] === 'assignment') {
                        Assignment::create([
                            'lesson_id' => $lesson->id,
                            'total_marks' => $lesData['marks'],
                            'passing_marks' => $lesData['marks'] * 0.5
                        ]);
                    }
                }
            }
        }
    }
}
