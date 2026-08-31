<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Instructor Portal') - {{ config('app.name', 'EduBridge') }}</title>
    <link rel="icon" href="{{ asset('images/edubridge_icon.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-[#F8F9FA] font-sans antialiased text-gray-900">
    @php
        $pendingGradingCount = \App\Models\AssignmentSubmission::where('status', 'pending')
            ->whereHas('assignment.lesson.module.course', function($query) {
                $query->where('instructor_id', auth()->id());
            })
            ->count();
    @endphp

    <div class="flex h-screen w-full">
        
        <aside class="w-[260px] bg-white border-r border-gray-200 flex flex-col h-screen shrink-0 overflow-hidden">
            <!-- Logo Section (Fixed) -->
            <div class="h-20 flex items-center px-6 border-b border-border">
                <a href="{{ route('instructor.dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/edubridge_icon.png') }}" alt="EduBridge Icon" class="w-10 h-10 rounded-xl shadow-sm">
                    <div class="flex flex-col">
                        <span class="text-xl font-black tracking-tighter text-charcoal leading-none">EduBridge</span>
                        <span class="text-[10px] font-black text-terracotta uppercase tracking-[0.15em] mt-1">Instructor Portal</span>
                    </div>
                </a>
            </div>

            <!-- Scrollable Navigation -->
            <div class="flex-1 overflow-y-auto custom-scrollbar">
                <nav class="mt-2 flex flex-col gap-1 px-3">
                    <!-- Dashboard -->
                    <a href="{{ route('instructor.dashboard') }}" class="flex items-center gap-3 px-3 py-2 transition text-sm font-medium {{ request()->routeIs('instructor.dashboard') ? 'bg-terracotta/10 text-terracotta font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Dashboard
                    </a>
                    
                    <!-- Courses -->
                    <div class="mt-2 text-[10px] font-black text-gray-400 uppercase tracking-widest px-3 mb-1">Learning</div>
                    <a href="{{ route('instructor.courses.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition text-sm font-medium {{ request()->is('instructor/course*') ? 'bg-terracotta/10 text-terracotta font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Courses
                        </div>
                    </a>
                    
                    @if(request()->is('instructor/course*'))
                    <div class="flex flex-col pl-11 gap-2 text-xs font-medium mt-1 mb-2 border-l border-gray-100 ml-5">
                        <a href="{{ route('instructor.courses.index') }}" class="{{ request()->routeIs('instructor.courses.index') || request()->routeIs('instructor.course.builder') ? 'text-terracotta font-semibold' : 'text-gray-500 hover:text-gray-900' }}">All Courses</a>
                    </div>
                    @endif

                    <!-- Assessments -->
                    <a href="{{ route('instructor.assessments.assignments') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition text-sm font-medium {{ request()->is('instructor/assessment*') ? 'bg-terracotta/10 text-terracotta font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Assessments
                        </div>
                    </a>
                    
                    <div class="flex flex-col pl-11 gap-2 text-xs font-medium mt-1 mb-2 border-l border-gray-100 ml-5 {{ request()->is('instructor/assessment*') ? 'block' : 'hidden' }}">
                        <a href="{{ route('instructor.assessments.quizzes') }}" class="{{ request()->routeIs('instructor.assessments.quizzes') ? 'text-terracotta font-semibold' : 'text-gray-500 hover:text-gray-900' }}">Quizzes</a>
                        <a href="{{ route('instructor.assessments.assignments') }}" class="{{ request()->routeIs('instructor.assessments.assignments') ? 'text-terracotta font-semibold' : 'text-gray-500 hover:text-gray-900' }}">Assignments</a>
                        <a href="{{ route('instructor.assessments.grading') }}" class="flex items-center justify-between {{ request()->routeIs('instructor.assessments.grading') ? 'text-terracotta font-semibold' : 'text-gray-500 hover:text-gray-900' }}">
                            Grading
                            @if($pendingGradingCount > 0)
                                <span class="bg-terracotta text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingGradingCount }}</span>
                            @endif
                        </a>
                    </div>

                    <!-- Students -->
                    <a href="{{ route('instructor.students.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-sm font-medium {{ request()->routeIs('instructor.students.*') ? 'bg-terracotta/10 text-terracotta font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Students
                    </a>
                    
                    <div class="mt-4 text-[10px] font-black text-gray-400 uppercase tracking-widest px-3 mb-1">Communication</div>
                    <a href="{{ route('instructor.announcements.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-sm font-medium {{ request()->routeIs('instructor.announcements.*') ? 'bg-terracotta/10 text-terracotta font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                        Announcements
                    </a>
                    
                    <a href="{{ route('instructor.reviews.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-sm font-medium {{ request()->routeIs('instructor.reviews.index') ? 'bg-terracotta/10 text-terracotta font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        Reviews
                    </a>
                    
                    <div class="mt-4 text-[10px] font-black text-gray-400 uppercase tracking-widest px-3 mb-1">Management</div>
                    <a href="{{ route('instructor.analytics') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-sm font-medium {{ request()->routeIs('instructor.analytics') ? 'bg-terracotta/10 text-terracotta font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Analytics
                    </a>
                    <a href="{{ route('instructor.earnings') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-sm font-medium {{ request()->routeIs('instructor.earnings') ? 'bg-terracotta/10 text-terracotta font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Earnings
                    </a>
                    <a href="{{ route('instructor.settings') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-sm font-medium mb-8 {{ request()->routeIs('instructor.settings') ? 'bg-terracotta/10 text-terracotta font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Settings
                    </a>
                    
                </nav>
            </div>

            <!-- Profile & Logout (Fixed) -->
            <div class="p-4 border-t border-gray-100 flex items-center justify-between shrink-0 bg-white">
                <a href="{{ route('instructor.settings') }}" class="flex items-center gap-3 min-w-0 flex-1 hover:opacity-80 transition">
                    <div class="w-9 h-9 rounded-full bg-gray-200 overflow-hidden shrink-0 border-2 border-terracotta/20">
                        @if(Auth::user()->profile_pic)
                            <img src="{{ asset('storage/' . Auth::user()->profile_pic) }}" alt="Profile" class="w-full h-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=E8674A&color=fff&bold=true" alt="Profile" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                    </div>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-500 transition" title="Logout">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-full overflow-hidden">
            <header class="h-[72px] bg-white border-b border-gray-200 flex items-center justify-between px-8 shrink-0 relative z-40">
                {{-- Left Side: Search --}}
                <div class="w-full max-w-xl">
                    <div class="relative flex items-center">
                        <svg class="w-5 h-5 absolute left-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" placeholder="Search courses, students..." class="w-full pl-10 pr-4 py-2 bg-[#F8F9FA] border border-gray-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-terracotta transition">
                    </div>
                </div>

                {{-- Right Side: Notifications --}}
                <div class="flex items-center gap-4">
                    @php
                        $unreadCount = auth()->user()->unreadNotifications->count();
                    @endphp
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" 
                                class="relative p-2 text-gray-400 hover:text-terracotta hover:bg-gray-50 rounded-full transition-all duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @if($unreadCount > 0)
                                <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full animate-pulse"></span>
                            @endif
                        </button>

                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute right-0 mt-3 w-80 bg-white border border-gray-100 rounded-2xl shadow-2xl z-50 overflow-hidden"
                             style="display: none;">
                            <div class="p-4 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                                <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Notifications</h4>
                                @if($unreadCount > 0)
                                    <span class="text-[10px] bg-terracotta/10 text-terracotta px-2 py-0.5 rounded-full font-bold">{{ $unreadCount }} New</span>
                                @endif
                            </div>
                            <div class="max-h-[350px] overflow-y-auto">
                                @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                    <div class="p-4 border-b border-gray-50 hover:bg-gray-50 transition cursor-pointer {{ $notification->unread() ? 'bg-indigo-50/10' : '' }}">
                                        <div class="flex gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-900 leading-snug">{!! $notification->data['message'] ?? 'New update' !!}</p>
                                                <p class="text-[10px] text-gray-400 mt-1 font-bold uppercase tracking-wider">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-8 text-center">
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">No notifications yet</p>
                                    </div>
                                @endforelse
                            </div>
                            <a href="{{ route('notifications.index') }}" class="block p-3 text-center text-xs font-bold text-terracotta hover:bg-gray-50 transition border-t border-gray-50">
                                View all notifications
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-8">
                @yield('content')
            </main>

        </div>
    </div>
</body>
</html>