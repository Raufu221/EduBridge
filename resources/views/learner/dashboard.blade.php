<x-app-layout>
    <div class="min-h-screen bg-cream pb-12" 
         x-data="{ 
            activeTab: 'active', 
            showReviewModal: false,
            showCertPreview: false,
            showLockedModal: false,
            lockedData: { progress: 0, average: 0, title: '' },
            certPreviewUrl: '',
            reviewCourseId: null,
            reviewCourseTitle: '',
            rating: 5
         }">
        
        <!-- 1. MODERN HERO SECTION -->
        <div class="bg-charcoal pt-12 pb-24 relative overflow-hidden">
            <!-- Decorative Gradients -->
            <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-indigo-500/10 to-transparent"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-400/10 text-amber-400 uppercase tracking-widest mb-4">
                            <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            Welcome Back
                        </span>
                        <h1 class="text-3xl md:text-4xl font-extrabold text-white leading-tight">
                            Your learning adventure <br>continues, {{ explode(' ', Auth::user()->name)[0] }} 🚀
                        </h1>
                        <p class="text-white/70 mt-4 max-w-xl text-sm font-medium">
                            Keep the momentum going! You've completed <strong>{{ $completedCount }}</strong> courses so far. What will you master next?
                        </p>
                    </div>

                 
                </div>

                <!-- SUB NAVIGATION TABS -->
                <div class="flex items-center gap-2 mt-12 overflow-x-auto pb-2 scrollbar-hide">
                    <button @click="activeTab = 'active'" :class="activeTab === 'active' ? 'bg-terracotta text-white shadow-lg shadow-terracotta/20' : 'text-white/60 hover:text-white hover:bg-white/5'" class="px-5 py-2.5 rounded-xl font-bold text-sm transition-all whitespace-nowrap">
                        Active Courses
                    </button>
                    <button @click="activeTab = 'completed'" :class="activeTab === 'completed' ? 'bg-terracotta text-white shadow-lg shadow-terracotta/20' : 'text-white/60 hover:text-white hover:bg-white/5'" class="px-5 py-2.5 rounded-xl font-bold text-sm transition-all whitespace-nowrap">
                        Completed
                    </button>
                    <button @click="activeTab = 'waitlists'" :class="activeTab === 'waitlists' ? 'bg-terracotta text-white shadow-lg shadow-terracotta/20' : 'text-white/60 hover:text-white hover:bg-white/5'" class="px-5 py-2.5 rounded-xl font-bold text-sm transition-all whitespace-nowrap flex items-center gap-2">
                        Waitlists & Saved
                        @if($waitlistedCourses->count() > 0)
                            <span class="w-5 h-5 flex items-center justify-center rounded-full bg-white/20 text-[10px] text-white">{{ $waitlistedCourses->count() }}</span>
                        @endif
                    </button>
                    <button @click="activeTab = 'certificates'" :class="activeTab === 'certificates' ? 'bg-terracotta text-white shadow-lg shadow-terracotta/20' : 'text-white/60 hover:text-white hover:bg-white/5'" class="px-5 py-2.5 rounded-xl font-bold text-sm transition-all whitespace-nowrap flex items-center gap-2">
                        My Certificates
                        @if($certificateCount > 0)
                            <span class="w-5 h-5 flex items-center justify-center rounded-full bg-white/20 text-[10px] text-white font-black">{{ $certificateCount }}</span>
                        @endif
                    </button>
                </div>
            </div>
        </div>

        <!-- 2. STATS BAR (OVERLAPPING HERO) -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative">
            <div class="bg-warm-white rounded-3xl shadow-sm border border-gray-100 p-2 sm:p-2">
                <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-gray-100">
                    <!-- Progress Stat -->
                    <div class="flex items-center gap-5 p-6">
                        <div class="relative w-16 h-16 shrink-0 flex items-center justify-center">
                            <svg class="w-full h-full transform -rotate-90">
                                <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="6" fill="transparent" class="text-gray-100" />
                                <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="6" fill="transparent" stroke-dasharray="176" stroke-dashoffset="{{ 176 - (176 * ($averageProgress / 100)) }}" class="text-terracotta stroke-round transition-all duration-1000" />
                            </svg>
                            <span class="absolute text-xs font-black text-gray-900">{{ $averageProgress }}%</span>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-gray-900">{{ $averageProgress }}%</p>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Average Progress</p>
                        </div>
                    </div>

                    <!-- Courses In Progress -->
                    <div class="flex items-center gap-5 p-6">
                        <div class="w-16 h-16 shrink-0 rounded-2xl bg-indigo-50 flex items-center justify-center">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-gray-900">{{ $inProgressCount }}</p>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Courses In Progress</p>
                        </div>
                    </div>

                    <!-- Certificates Count -->
                    <div class="flex items-center gap-5 p-6">
                        <div class="w-16 h-16 shrink-0 rounded-2xl bg-amber-50 flex items-center justify-center">
                            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-gray-900">{{ $certificateCount }}</p>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Certificates Earned</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. MAIN DASHBOARD CONTENT -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 space-y-12">
            
            {{-- RECENT ANNOUNCEMENTS WIDGET --}}
            @if($announcements->count() > 0)
            <section x-show="activeTab === 'active'">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-2 text-rose-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                        <h2 class="text-xl font-black tracking-tight">Recent Announcements</h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($announcements as $ann)
                        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition-all relative overflow-hidden group">
                            {{-- Admin Badge --}}
                            @if(!$ann->course_id)
                                <div class="absolute top-0 right-0">
                                    <span class="bg-indigo-600 text-white text-[8px] font-black uppercase tracking-widest px-3 py-1 rounded-bl-xl shadow-sm">Global Alert</span>
                                </div>
                            @endif

                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-2xl {{ $ann->course_id ? 'bg-amber-50 text-amber-600' : 'bg-indigo-50 text-indigo-600' }} flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ $ann->created_at->diffForHumans() }}</p>
                                    <h4 class="text-sm font-bold text-gray-900 truncate">{{ $ann->title }}</h4>
                                    <p class="text-xs text-gray-500 mt-2 line-clamp-2 leading-relaxed">{{ $ann->content }}</p>
                                    
                                    @if($ann->course_id)
                                        <a href="{{ route('learner.course.viewer', $ann->course_id) }}" class="inline-flex items-center gap-1 text-[10px] font-black text-amber-500 mt-4 uppercase tracking-widest hover:translate-x-1 transition-transform">
                                            Go to Course
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
            @endif
            
            <!-- A. JUMP BACK IN SECTION -->
            @if($recentEnrollment)
            <section x-show="activeTab === 'active'">
                <div class="flex items-center gap-2 mb-6 text-indigo-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                    <h2 class="text-xl font-black tracking-tight">Jump Back In</h2>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-md transition-all duration-300">
                    <div class="flex flex-col lg:flex-row h-full">
                        <!-- Left: Image -->
                        <div class="lg:w-[400px] h-64 lg:h-auto overflow-hidden relative">
                            @if($recentEnrollment->course->cover_image)
                                <img src="{{ asset('storage/' . $recentEnrollment->course->cover_image) }}" class="w-full h-full object-contain object-center group-hover:scale-105 transition duration-700">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-[#0f172a]/10 group-hover:bg-transparent transition duration-500"></div>
                        </div>

                        <!-- Right: Details -->
                        <div class="flex-1 p-8 flex flex-col justify-center">
                            <div class="mb-4">
                                <span class="bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full">
                                    {{ $recentEnrollment->course->category->name }}
                                </span>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-black text-gray-900 mb-2 leading-tight">
                                {{ $recentEnrollment->course->title }}
                            </h3>
                            <div class="flex items-center justify-between mb-8">
                                <p class="text-sm text-gray-500 font-medium">
                                    Instructor: <span class="text-gray-900 border-b border-gray-200">{{ $recentEnrollment->course->instructor->name }}</span>
                                </p>
                                @if(!$recentEnrollment->has_reviewed)
                                    <button @click="showReviewModal = true; reviewCourseId = '{{ $recentEnrollment->course->id }}'; reviewCourseTitle = '{{ addslashes($recentEnrollment->course->title) }}'" class="text-xs font-black text-amber-500 hover:text-amber-600 flex items-center gap-1 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        Rate Course
                                    </button>
                                @endif
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between text-sm font-bold">
                                    <span class="text-gray-900">Lesson {{ $recentEnrollment->completed_lessons }} of {{ $recentEnrollment->total_lessons }}</span>
                                    <span class="text-indigo-600">{{ $recentEnrollment->progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                                    <div class="bg-indigo-600 h-full rounded-full transition-all duration-1000" style="width: {{ $recentEnrollment->progress }}%"></div>
                                </div>
                                <div class="pt-4">
                                    <a href="{{ route('learner.course.viewer', $recentEnrollment->course) }}" class="inline-flex items-center justify-center px-8 py-4 bg-indigo-600 text-white rounded-2xl font-black text-lg hover:bg-indigo-700 shadow-xl shadow-indigo-600/20 hover:-translate-y-1 transition duration-300 transform active:scale-95">
                                        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                                        Resume Learning
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            @endif

            <!-- B. ACTIVE COURSES GRID -->
            <section x-show="activeTab === 'active'">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-2 text-gray-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        <h2 class="text-xl font-black tracking-tight">Active Courses</h2>
                    </div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $inProgressCount }} courses found</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($enrollments as $enrollment)
                        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden group flex flex-col h-full">
                            <div class="relative aspect-video overflow-hidden">
                                @if($enrollment->course->cover_image)
                                    <img src="{{ asset('storage/' . $enrollment->course->cover_image) }}" class="w-full h-full object-contain object-center group-hover:scale-110 transition duration-1000">
                                @else
                                    <div class="w-full h-full bg-indigo-50 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    </div>
                                @endif
                                <div class="absolute top-4 left-4">
                                    <span class="bg-white/90 backdrop-blur-sm px-2.5 py-1 rounded-lg text-[10px] font-black uppercase text-gray-600 shadow-sm border border-gray-100">
                                        {{ $enrollment->course->category->name }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="p-6 flex flex-col flex-1">
                                <h3 class="text-lg font-black text-gray-900 mb-2 leading-tight group-hover:text-indigo-600 transition-colors">
                                    {{ $enrollment->course->title }}
                                </h3>
                                <div class="flex items-center justify-between mb-4">
                                    <p class="text-xs text-gray-500 font-bold uppercase tracking-tight">Instructor: {{ $enrollment->course->instructor->name }}</p>
                                    @if(!$enrollment->has_reviewed)
                                        <button @click="showReviewModal = true; reviewCourseId = '{{ $enrollment->course->id }}'; reviewCourseTitle = '{{ addslashes($enrollment->course->title) }}'" class="text-[10px] font-black text-amber-500 hover:text-amber-600 flex items-center gap-1 transition-colors">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            Rate
                                        </button>
                                    @else
                                        <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            Reviewed
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-auto space-y-3">
                                    <div class="flex items-center justify-between text-[11px] font-black">
                                        <span class="text-gray-400 uppercase tracking-widest">Progress</span>
                                        <span class="text-indigo-600">{{ $enrollment->progress }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden" title="{{ $enrollment->completed_lessons }} / {{ $enrollment->total_lessons }} lessons">
                                        <div class="bg-indigo-600 h-full rounded-full transition-all duration-1000" style="width: {{ $enrollment->progress }}%"></div>
                                    </div>
                                    <div class="pt-2 flex gap-2">
                                        <a href="{{ route('learner.course.viewer', $enrollment->course) }}" class="flex-1 flex items-center justify-center py-3 bg-white text-indigo-600 border-2 border-indigo-50 rounded-2xl text-xs font-black hover:bg-indigo-50 transition duration-300">
                                            Continue Learning
                                        </a>
                                        <button @click="showLockedModal = true; lockedData = { progress: {{ $enrollment->progress }}, average: {{ $enrollment->average_score }}, title: '{{ addslashes($enrollment->course->title) }}' }" 
                                                class="px-4 bg-gray-50 text-gray-400 rounded-2xl hover:bg-gray-100 transition border border-gray-100" title="Check Requirements">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center bg-white rounded-3xl border-2 border-dashed border-gray-200">
                            <p class="text-gray-400 font-bold">You aren't active in any courses yet</p>
                            <a href="{{ route('courses.index') }}" class="inline-block mt-4 text-indigo-600 font-black underline">Browse Catalog</a>
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- C. COMPLETED COURSES -->
            <section x-show="activeTab === 'completed'" style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($enrollments->where('progress', 100) as $enrollment)
                        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden group flex flex-col h-full opacity-90 hover:opacity-100 transition duration-300">
                            <div class="relative aspect-video overflow-hidden">
                                @if($enrollment->course->cover_image)
                                    <img src="{{ asset('storage/' . $enrollment->course->cover_image) }}" class="w-full h-full object-contain object-center grayscale-[0.5] group-hover:grayscale-0 transition duration-500">
                                @else
                                    <div class="w-full h-full bg-gray-50 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-emerald-500/10 flex items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-white/90 flex items-center justify-center text-emerald-500 shadow-lg">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-6 flex flex-col flex-1">
                                <h3 class="font-black text-gray-900 mb-2 leading-tight">{{ $enrollment->course->title }}</h3>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="inline-flex items-center px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-black uppercase tracking-widest">
                                        Completed
                                    </span>
                                    @if(!$enrollment->has_reviewed)
                                        <button @click="showReviewModal = true; reviewCourseId = '{{ $enrollment->course->id }}'; reviewCourseTitle = '{{ addslashes($enrollment->course->title) }}'" class="text-[10px] font-black text-amber-500 hover:text-amber-600 flex items-center gap-1 transition-colors">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            Give Feedback
                                        </button>
                                    @endif
                                </div>
                                <div class="mt-auto pt-4 flex gap-2">
                                    <a href="{{ route('learner.course.viewer', $enrollment->course) }}" class="flex-1 flex items-center justify-center py-3 bg-gray-50 text-gray-600 rounded-2xl text-[11px] font-black hover:bg-gray-100 transition">
                                        Review Material
                                    </a>
                                @if($enrollment->certificate)
                                    <button @click="showCertPreview = true; certPreviewUrl = '{{ route('learner.certificate.preview', $enrollment->certificate) }}'" 
                                            class="px-3 bg-amber-50 text-amber-600 rounded-2xl hover:bg-amber-100 transition" title="Preview Certificate">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                    <a href="{{ route('learner.certificate.download', $enrollment->certificate) }}" 
                                       class="px-3 flex items-center justify-center bg-indigo-50 text-indigo-600 rounded-2xl hover:bg-indigo-100 transition" title="Download Certificate">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0L8 8m4-4v12"></path></svg>
                                    </a>
                                @else
                                    @if($enrollment->is_eligible)
                                        <a href="{{ route('learner.course.viewer', $enrollment->course) }}" 
                                           class="px-3 flex items-center justify-center bg-amber-50 text-amber-600 rounded-2xl hover:bg-amber-100 transition text-[10px] font-black uppercase tracking-tight">
                                            Claim
                                        </a>
                                    @else
                                        <button @click="showLockedModal = true; lockedData = { progress: {{ $enrollment->progress }}, average: {{ $enrollment->average_score }}, title: '{{ addslashes($enrollment->course->title) }}' }" 
                                                class="px-4 bg-red-50 text-red-400 rounded-2xl hover:bg-red-100 transition border border-red-100" title="Locked: Check Requirements">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </button>
                                    @endif
                                @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center bg-white rounded-3xl border-2 border-dashed border-gray-200">
                            <p class="text-gray-400 font-bold">You haven't completed any courses yet. Keep pushing! 🚀</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- D. WAITLISTS & SAVED -->
            <section x-show="activeTab === 'waitlists'" style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($waitlistedCourses as $waitlist)
                        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden group flex flex-col h-full">
                            <div class="relative aspect-video overflow-hidden bg-gray-100">
                                @if($waitlist->course->cover_image)
                                    <img src="{{ asset('storage/' . $waitlist->course->cover_image) }}" class="w-full h-full object-contain object-center group-hover:scale-110 transition duration-1000">
                                @else
                                    <div class="w-full h-full bg-indigo-50 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    </div>
                                @endif
                                <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                                    <span class="bg-indigo-600 text-white px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm">Waitlisted</span>
                                    @if($waitlist->notified_at)
                                        <span class="bg-emerald-500 text-white px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm animate-pulse">Seat Available!</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="p-6 flex flex-col flex-1">
                                <h3 class="text-lg font-black text-gray-900 mb-2 leading-tight group-hover:text-indigo-600 transition-colors">
                                    {{ $waitlist->course->title }}
                                </h3>
                                <p class="text-xs text-gray-500 font-bold uppercase tracking-tight mb-8">Instructor: {{ $waitlist->course->instructor->name }}</p>
                                
                                <div class="flex items-center justify-between gap-4 mt-auto">
                                    <a href="{{ route('courses.show', $waitlist->course) }}" class="flex-1 py-3.5 bg-indigo-600 text-white text-xs font-black rounded-2xl text-center hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                                        Course Page
                                    </a>
                                    <form action="{{ route('learner.course.waitlist.destroy', $waitlist->course) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-3.5 bg-gray-50 hover:bg-red-50 text-gray-400 hover:text-red-500 rounded-2xl border border-gray-100 transition" title="Leave Waitlist">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center bg-white rounded-3xl border-2 border-dashed border-gray-200">
                            <p class="text-gray-400 font-bold">Your waitlist is empty.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- E. MY CERTIFICATES -->
            <section x-show="activeTab === 'certificates'" style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($certificates as $cert)
                        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden group p-6 flex flex-col items-center text-center">
                            <div class="w-20 h-20 bg-amber-50 rounded-full flex items-center justify-center mb-6 border-4 border-white shadow-md relative overflow-hidden">
                                <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                <div class="absolute inset-0 bg-gradient-to-tr from-amber-400/20 to-transparent"></div>
                            </div>
                            
                            <h3 class="font-black text-gray-900 mb-1 leading-tight group-hover:text-amber-600 transition-colors">{{ $cert->course->title }}</h3>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Issued: {{ $cert->issue_date->format('M d, Y') }}</p>
                            
                            <div class="w-full bg-gray-50 rounded-2xl p-4 mb-6 text-left">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">Average Score</span>
                                    <span class="text-sm font-black text-indigo-600">{{ round($cert->average_score, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 h-1 rounded-full overflow-hidden">
                                    <div class="bg-indigo-600 h-full" style="width: {{ $cert->average_score }}%"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-2 w-full">
                                <button @click="showCertPreview = true; certPreviewUrl = '{{ route('learner.certificate.preview', $cert->id) }}'" 
                                        class="flex items-center justify-center py-3 bg-amber-50 text-amber-600 rounded-xl text-[10px] font-black hover:bg-amber-100 transition uppercase tracking-widest border border-amber-100" title="Preview">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                                <a href="{{ route('learner.certificate.download', $cert->id) }}" class="flex items-center justify-center py-3 bg-indigo-600 text-white rounded-xl text-[10px] font-black hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 uppercase tracking-widest" title="Download">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0L8 8m4-4v12"></path></svg>
                                </a>
                                <a href="{{ route('certificate.verify', $cert->certificate_code) }}" target="_blank" class="flex items-center justify-center py-3 bg-white text-gray-600 border border-gray-100 rounded-xl text-[10px] font-black hover:bg-gray-50 transition uppercase tracking-widest" title="Verify">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 text-center bg-white rounded-3xl border-2 border-dashed border-gray-100">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <h3 class="text-lg font-black text-gray-900 mb-2">No Certificates Yet</h3>
                            <p class="text-gray-400 max-w-xs mx-auto font-medium">Complete courses with an 80% average to see your achievements here!</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </main>

        <!-- 6. LOCKED REQUIREMENTS MODAL -->
        <div x-show="showLockedModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[120] overflow-y-auto" style="display: none;">
            
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 transition-opacity bg-black/80 backdrop-blur-md" @click="showLockedModal = false"></div>

                <div class="relative inline-block w-full max-w-md overflow-hidden transition-all transform bg-slate-900 shadow-2xl rounded-[2.5rem] border border-slate-800"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                    
                    <div class="p-8 text-center pt-12">
                        <!-- Huge Lock Icon -->
                        <div class="w-32 h-32 bg-slate-800/50 rounded-full flex items-center justify-center mx-auto mb-8 border border-red-500/20 shadow-2xl shadow-red-500/10 relative">
                            <svg class="w-12 h-12 text-red-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>

                        <h3 class="text-2xl font-black text-white mb-2" x-text="lockedData.title"></h3>
                        <p class="text-xs font-bold text-red-400 uppercase tracking-[0.3em] mb-10">Certification Locked</p>

                        <div class="space-y-4 text-left bg-black/30 p-6 rounded-3xl border border-slate-800">
                            <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4">Requirements Checklist:</h4>
                            
                            <!-- Progress Req -->
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <template x-if="lockedData.progress >= 100">
                                        <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    </template>
                                    <template x-if="lockedData.progress < 100">
                                        <svg class="w-5 h-5 text-slate-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                    </template>
                                    <span class="text-sm font-bold" :class="lockedData.progress >= 100 ? 'text-white' : 'text-slate-400'">Complete 100% of Course</span>
                                </div>
                                <span class="text-xs font-black" :class="lockedData.progress >= 100 ? 'text-emerald-400' : 'text-slate-500'" x-text="lockedData.progress + '%'"></span>
                            </div>

                            <!-- Score Req -->
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <template x-if="lockedData.average >= 80">
                                        <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    </template>
                                    <template x-if="lockedData.average < 80">
                                        <svg class="w-5 h-5 text-slate-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                    </template>
                                    <span class="text-sm font-bold" :class="lockedData.average >= 80 ? 'text-white' : 'text-slate-400'">Average Score ≥ 80%</span>
                                </div>
                                <span class="text-xs font-black" :class="lockedData.average >= 80 ? 'text-emerald-400' : 'text-slate-500'" x-text="Math.round(lockedData.average) + '%'"></span>
                            </div>
                        </div>

                        <div class="mt-10">
                            <button @click="showLockedModal = false" class="w-full py-4 bg-slate-800 text-white rounded-2xl font-black text-sm hover:bg-slate-700 transition">
                                Got it
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 7. CERTIFICATE PREVIEW MODAL -->
        <div x-show="showCertPreview" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[120] overflow-y-auto" style="display: none;">
            
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 transition-opacity bg-black/80 backdrop-blur-md" @click="showCertPreview = false"></div>

                <div class="relative w-full max-w-5xl h-[85vh] overflow-hidden transition-all transform bg-white shadow-2xl rounded-[2.5rem] border border-gray-100 flex flex-col"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                    
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between px-8 py-5 border-b border-gray-100 bg-gray-50/50">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                            <h3 class="text-lg font-black text-gray-900">Certificate Preview</h3>
                        </div>
                        <button @click="showCertPreview = false" class="p-2 hover:bg-gray-100 rounded-xl text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Modal Body / IFrame -->
                    <div class="flex-1 bg-slate-50 relative">
                        <iframe :src="certPreviewUrl" class="w-full h-full border-none bg-white" allowfullscreen></iframe>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end gap-3 px-8 py-5 border-t border-gray-100 bg-gray-50/50">
                        <button @click="showCertPreview = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl font-bold text-sm transition">
                            Close Preview
                        </button>
                        <a :href="certPreviewUrl ? certPreviewUrl.replace('/preview', '/download') : '#'" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-sm transition shadow-lg shadow-indigo-600/20">
                            Download PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
