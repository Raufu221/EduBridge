<x-app-layout>
<div class="bg-white min-h-screen">
    <!-- Premium Hero Section -->
    <div class="bg-gray-900 border-b border-gray-800 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1516321497487-e288fb19713f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80')] bg-cover bg-center opacity-10"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-900/90 to-transparent"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 relative z-10">
            <div class="max-w-3xl">
                <nav class="flex text-sm text-gray-400 mb-6 font-medium">
                    <ol class="flex items-center space-x-2">
                        <li><a href="{{ route('courses.index') }}" class="hover:text-white transition">Catalog</a></li>
                        <li><span class="px-1">&gt;</span></li>
                        <li><span class="text-indigo-400">{{ $course->category->name ?? 'General' }}</span></li>
                    </ol>
                </nav>
                
                <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 leading-tight">
                    {{ $course->title }}
                </h1>
                
                <p class="text-lg sm:text-xl text-gray-300 mb-8 leading-relaxed">
                    {{ $course->description }}
                </p>

                <div class="flex flex-wrap items-center gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center font-bold text-white border-2 border-gray-800 shadow-md">
                            {{ substr($course->instructor->name ?? 'I', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-white leading-none">Created by {{ $course->instructor->name ?? 'Instructor' }}</p>
                            <span class="text-indigo-300 text-xs">Platform Expert</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 text-amber-500 font-bold bg-gray-800/50 px-3 py-1.5 rounded-lg border border-gray-700">
                    <div class="flex items-center gap-2 text-amber-500 font-bold bg-gray-800/50 px-3 py-1.5 rounded-lg border border-gray-700">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <span>
                            @if($course->reviews_count > 0)
                                {{ $course->average_rating }} ({{ $course->reviews_count }} ratings)
                            @else
                                NEW
                            @endif
                        </span>
                    </div>

                    <div class="flex items-center gap-2 text-gray-300 bg-gray-800/50 px-3 py-1.5 rounded-lg border border-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Last updated {{ $course->updated_at->format('M Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 relative">
            
            <!-- Left Column: Syllabus & Details -->
            <div class="lg:col-span-2 space-y-12">
                
            <!-- What you'll learn (Dynamic Plain Text String) -->
            <section class="bg-gray-50 border border-gray-100 rounded-3xl p-8 shadow-sm">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">What you'll learn</h2>
                <div class="text-gray-600 font-medium whitespace-pre-line leading-relaxed">
                    {{ $course->what_you_will_learn }}
                </div>
            </section>

                <!-- Course Syllabus -->
                <section>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Course Content</h2>
                        <span class="text-sm font-semibold text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                            {{ $course->modules->count() }} modules • {{ $course->modules->sum(function($module){ return $module->lessons->count(); }) }} lessons
                        </span>
                    </div>

                    <div class="space-y-4" x-data="{ activeAccordion: 0 }">
                        @forelse($course->modules as $index => $module)
                            <div class="border border-gray-200 rounded-2xl overflow-hidden bg-white shadow-sm transition-all duration-200"
                                 :class="{ 'ring-2 ring-indigo-500 border-transparent': activeAccordion === {{ $index }} }">
                                <button class="w-full flex items-center justify-between p-5 focus:outline-none"
                                        @click="activeAccordion = activeAccordion === {{ $index }} ? null : {{ $index }}">
                                    <div class="flex items-center gap-4 text-left">
                                        <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm shrink-0">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900">{{ $module->title }}</h3>
                                            <p class="text-xs text-gray-500 font-medium mt-1">{{ $module->lessons->count() }} lectures</p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 transform transition-transform" 
                                         :class="{'rotate-180 text-indigo-600': activeAccordion === {{ $index }}}" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <div x-show="activeAccordion === {{ $index }}" x-collapse class="border-t border-gray-100 bg-gray-50">
                                    <ul class="divide-y divide-gray-100">
                                        @forelse($module->lessons as $lesson)
                                            <li class="p-4 pl-[4.5rem] hover:bg-gray-100 transition flex items-center justify-between group">
                                                <div class="flex items-center gap-3">
                                                    @if($lesson->type === 'video')
                                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-500 transition-colors shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                                                    @else
                                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-500 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    @endif
                                                    <span class="text-sm font-medium text-gray-700">{{ $lesson->title }}</span>
                                                </div>
                                            </li>
                                        @empty
                                            <li class="p-4 pl-[4.5rem] text-sm text-gray-400 italic">Content is actively being uploaded.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center border-2 border-dashed border-gray-200 rounded-3xl text-gray-500">
                                This course curriculum is currently being developed.
                            </div>
                        @endforelse
                    </div>
                </section>
                
                <!-- Instructor -->
                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-t border-gray-100 pt-12">Meet your Instructor</h2>
                    <div class="flex items-start gap-6">
                        <div class="w-24 h-24 rounded-full bg-indigo-600 flex flex-shrink-0 items-center justify-center text-3xl font-extrabold text-white shadow-lg overflow-hidden relative group">
                            {{ substr($course->instructor->name ?? '?', 0, 1) }}
                            <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $course->instructor->name ?? 'Deleted Instructor' }}</h3>
                            <p class="text-indigo-600 font-semibold mb-4">{{ $course->instructor->email ?? '' }}</p>
                            <div class="prose prose-sm text-gray-600 max-w-none mb-4 line-clamp-4">
                                {{ $course->instructor->instructorApplication->bio ?? 'An expert instructor passionate about teaching on EduBridge.' }}
                            </div>
                            @if(isset($course->instructor->instructorApplication->portfolio_url))
                                <a href="{{ $course->instructor->instructorApplication->portfolio_url }}" target="_blank" class="inline-flex items-center gap-1.5 text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    View Web Portfolio
                                </a>
                            @endif
                        </div>
                    </div>
                </section>

                <!-- Student Feedback Section -->
                <section class="border-t border-gray-100 pt-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8">Student Feedback</h2>
                    
                    <div class="flex flex-col md:flex-row gap-8 mb-12 items-center bg-gray-50 rounded-3xl p-8 border border-gray-100">
                        <div class="text-center">
                            <div class="text-6xl font-black text-indigo-600 mb-2">{{ $course->average_rating }}</div>
                            <div class="flex justify-center text-amber-500 mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5" fill="{{ $i <= round($course->average_rating) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endfor
                            </div>
                            <div class="text-sm font-bold text-gray-500 uppercase tracking-widest">Course Rating</div>
                        </div>
                        
                        <div class="flex-1 space-y-3 w-full">
                            @for($rating = 5; $rating >= 1; $rating--)
                                @php
                                    $count = $course->reviews()->where('rating', $rating)->where('is_hidden', false)->count();
                                    $totalReviews = $course->reviews()->where('is_hidden', false)->count();
                                    $percent = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                @endphp
                                <div class="flex items-center gap-4">
                                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden flex-1">
                                        <div class="bg-amber-400 h-full" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <div class="flex items-center gap-1 w-20 shrink-0">
                                        <span class="text-xs font-bold text-gray-500 mr-1">{{ $rating }} ⭐</span>
                                        <span class="text-xs font-bold text-gray-400">({{ round($percent) }}%)</span>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div class="space-y-8">
                        @forelse($course->reviews()->where('is_hidden', false)->with('user')->latest()->get() as $review)
                            <div class="space-y-4">
                                <div class="flex gap-4 p-6 bg-white border border-gray-100 rounded-2xl shadow-sm">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-500 shrink-0">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 text-left">
                                        <div class="flex items-center justify-between mb-1">
                                            <h4 class="font-bold text-gray-900">{{ $review->user->name }}</h4>
                                            <span class="text-xs text-gray-400 font-medium">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="flex text-amber-500 mb-3">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-3.5 h-3.5" fill="{{ $i <= $review->rating ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            @endfor
                                        </div>
                                        <p class="text-gray-600 text-sm leading-relaxed">{{ $review->comment }}</p>
                                    </div>
                                </div>

                                @if($review->instructor_reply)
                                    <div class="ml-12 flex gap-4 p-5 bg-indigo-50 border border-indigo-100 rounded-2xl relative">
                                        <div class="absolute -top-4 left-6 w-0.5 h-6 bg-gray-200"></div>
                                        <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center font-bold text-white shrink-0 shadow-sm">
                                            {{ substr($course->instructor->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1 text-left">
                                            <div class="flex items-center gap-2 mb-1">
                                                <h4 class="font-bold text-indigo-900 text-sm italic">Instructor Response</h4>
                                                <span class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest">Official</span>
                                            </div>
                                            <p class="text-indigo-700 text-sm leading-relaxed">{{ $review->instructor_reply }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-12 bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                                <p class="text-gray-500 font-medium">No reviews yet. Be the first to share your experience!</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

            <!-- Right Column: Sticky Enrollment Box -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden z-20 shadow-indigo-100/50">
                    <div class="w-full aspect-video bg-gray-100 relative group border-b border-gray-100">
                        @if($course->cover_image)
                            <img src="{{ asset('storage/' . $course->cover_image) }}" alt="Preview" class="w-full h-full object-cover">
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                                <svg class="w-16 h-16 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition-colors flex items-center justify-center cursor-pointer">
                            <div class="w-16 h-16 rounded-full bg-white/95 backdrop-blur-sm flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-indigo-600 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>
                        <div class="absolute bottom-4 left-4 bg-gray-900/80 backdrop-blur-md text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Preview Course</div>
                    </div>
                    
                    <div class="p-8">
                        <div class="mb-6">
                            <span class="text-4xl font-extrabold text-gray-900">
                                {{ $course->price == 0 ? 'Free' : '৳'.number_format($course->price, 0) }}
                            </span>
                        </div>
                        
                        @php
                            $isFull = $course->max_students && $course->enrollments_count >= $course->max_students;
                            $remainingSeats = $course->max_students ? $course->max_students - $course->enrollments_count : null;
                            $fillPercent = $course->max_students ? ($course->enrollments_count / $course->max_students) * 100 : 0;
                        @endphp

                        @if($course->max_students)
                            <div class="mb-6">
                                <div class="flex justify-between items-end mb-2">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Enrollment Status</span>
                                    <span class="text-xs font-bold {{ $isFull ? 'text-red-500' : 'text-indigo-600' }}">
                                        @if($isFull)
                                            Sold Out
                                        @else
                                            {{ $course->enrollments_count }} / {{ $course->max_students }} Joined
                                        @endif
                                    </span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2 mb-2 overflow-hidden">
                                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-full rounded-full transition-all duration-1000" style="width: {{ $fillPercent }}%"></div>
                                </div>
                                @if(!$isFull && $remainingSeats <= 10)
                                    <div class="flex items-center gap-2 text-[11px] font-bold text-amber-600 animate-pulse">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path></svg>
                                        HURRY! ONLY {{ $remainingSeats }} {{ Str::plural('SEAT', $remainingSeats) }} LEFT
                                    </div>
                                @elseif($isFull)
                                    <div class="flex items-center gap-2 text-[11px] font-bold text-red-500">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        MAXIMUM CAPACITY REACHED
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="mb-6 flex items-center gap-2 py-2 px-3 bg-indigo-50 border border-indigo-100 rounded-lg">
                                <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                                <span class="text-xs font-bold text-indigo-700">Join {{ $course->enrollments_count }} other students</span>
                            </div>
                        @endif
                        
                        @auth
                            @php
                                $isEnrolled = Auth::user()->enrollments()->where('course_id', $course->id)->exists();
                                $isWaitlisted = Auth::user()->waitlists()->where('course_id', $course->id)->exists();
                            @endphp

                            @if($isEnrolled)
                                <a href="{{ route('learner.course.viewer', $course) }}" class="w-full py-4 bg-emerald-600 text-white rounded-xl font-bold text-lg hover:bg-emerald-700 transition shadow-lg active:translate-y-0 text-center flex justify-center items-center gap-2 mb-4">
                                    Go to Course
                                </a>
                            @elseif($isFull)
                                @if($isWaitlisted)
                                    <button disabled class="w-full py-4 bg-indigo-50 text-indigo-400 border border-indigo-200 rounded-xl font-bold text-lg cursor-not-allowed shadow-inner text-center flex justify-center items-center gap-2 mb-2">
                                        ✅ On Waitlist
                                    </button>
                                    
                                    <form action="{{ route('learner.course.waitlist.destroy', $course) }}" method="POST" class="text-center">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 font-semibold hover:text-red-700 underline transition underline-offset-4">
                                            Leave Waitlist
                                        </button>
                                    </form>
                                    
                                    <p class="text-center text-xs font-bold text-indigo-500 uppercase tracking-widest mt-4">You'll be notified when seats open up</p>
                                @else
                                    <form action="{{ route('learner.course.waitlist', $course) }}" method="POST" class="mb-4">
                                        @csrf
                                        <button type="submit" class="w-full py-4 bg-indigo-100 text-indigo-700 border-2 border-dashed border-indigo-300 rounded-xl font-bold text-lg hover:bg-indigo-200 transition shadow-md text-center flex justify-center items-center gap-2">
                                            📥 Join Waitlist
                                        </button>
                                    </form>
                                    <p class="text-center text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-4">Join <strong>{{ $course->waitlists()->count() }} others</strong> waiting for a spot</p>
                                @endif
                            @else
                                @if($course->price > 0)
                                    <a href="{{ route('learner.checkout.show', $course->id) }}" class="w-full py-4 bg-indigo-600 text-white rounded-xl font-bold text-lg hover:bg-indigo-700 transition shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 text-center flex justify-center items-center gap-2 mb-4">
                                        Enroll Now
                                    </a>
                                @else
                                    <form action="{{ route('learner.course.enroll', $course) }}" method="POST" class="mb-4">
                                        @csrf
                                        <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-xl font-bold text-lg hover:bg-indigo-700 transition shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 text-center flex justify-center items-center gap-2">
                                            Enroll for Free
                                        </button>
                                    </form>
                                @endif
                            @endif
                        @else
                            @if($isFull)
                                <form action="{{ route('learner.course.waitlist', $course) }}" method="POST" class="mb-4">
                                    @csrf
                                    <button type="submit" class="w-full py-4 bg-indigo-100 text-indigo-700 border-2 border-dashed border-indigo-300 rounded-xl font-bold text-lg hover:bg-indigo-200 transition shadow-md text-center flex justify-center items-center gap-2">
                                        📥 Join Waitlist
                                    </button>
                                </form>
                                <p class="text-center text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-4">Course is currently at full capacity</p>
                            @else
                                <a href="{{ route('login') }}" class="w-full py-4 bg-indigo-600 text-white rounded-xl font-bold text-lg hover:bg-indigo-700 transition shadow-lg hover:shadow-xl text-center flex justify-center items-center gap-2 mb-4">
                                    Login to Enroll
                                </a>
                            @endif
                        @endauth
                        
                        <p class="text-xs text-center text-gray-400 font-medium mb-8">30-Day Money-Back Guarantee</p>

                        <ul class="space-y-4 text-sm font-medium text-gray-600">
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                Comprehensive curriculum
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                Full lifetime access
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                Certificate of completion
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
</x-app-layout>
