@extends('layouts.instructor')

@section('title', 'Dashboard Overview')

@section('content')
<div class="min-h-screen bg-cream">
    <!-- Premium Welcome Header Section -->
    <div class="mb-10 relative overflow-hidden">
        <div class="flex items-center gap-4">
            <div class="relative">
                <h1 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tighter leading-none">
                    Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-terracotta to-[#E8674A]">{{ auth()->user()->name }}</span>
                    <span class="inline-block animate-wave origin-[70%_70%] text-4xl">👋</span>
                </h1>
                <p class="text-gray-500 font-medium mt-3 text-lg">It's a great day to share some knowledge! Here is your daily overview.</p>
            </div>
        </div>

        {{-- PLATFORM ANNOUNCEMENTS (NEW) --}}
        @if($announcements->count() > 0)
        <div class="mt-12 bg-[#da572f1a] rounded-[2.5rem] p-8 text-[#da572f] relative overflow-hidden shadow-2xl shadow-indigo-200 group">
            <div class="absolute -top-12 -right-12 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    </div>
                    <h2 class="text-xl font-black tracking-tight uppercase">Important Platform Updates</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($announcements as $ann)
                        <div class="bg-white/10 backdrop-blur-md rounded-3xl p-6 border border-white/10 hover:bg-white/20 transition-all cursor-default">
                            <div class="flex justify-between items-start mb-3">
                                <h4 class="font-bold text-lg leading-tight">{{ $ann->title }}</h4>
                            </div>
                            <p class="text-sm opacity-80 line-clamp-3 leading-relaxed mb-4">{{ $ann->content }}</p>
                            <span class="text-[10px] font-black uppercase tracking-widest opacity-60">{{ $ann->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <style>
            @keyframes wave-animation {
                0% { transform: rotate( 0.0deg) }
               10% { transform: rotate(14.0deg) }
               20% { transform: rotate(-8.0deg) }
               30% { transform: rotate(14.0deg) }
               40% { transform: rotate(-4.0deg) }
               50% { transform: rotate(10.0deg) }
               60% { transform: rotate( 0.0deg) }
              100% { transform: rotate( 0.0deg) }
            }
            .animate-wave {
                animation-name: wave-animation;
                animation-duration: 2.5s;
                animation-iteration-count: infinite;
                display: inline-block;
            }
        </style>
    </div>

    <!-- Quick Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Students Metric -->
        <div class="bg-warm-white rounded-2xl border border-border p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-terracotta/10 flex items-center justify-center text-terracotta">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <span class="text-sage text-sm font-bold bg-sage/10 px-2 py-1 rounded-lg">↗ +12%</span>
            </div>
            <h3 class="text-muted text-[11px] font-black uppercase tracking-widest mb-1">Total Students</h3>
            <p class="text-2xl font-black text-charcoal tracking-tight">{{ number_format($totalStudents) }}</p>
        </div>

        <!-- Revenue Metric -->
        <div class="bg-warm-white rounded-2xl border border-border p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-gold/10 flex items-center justify-center text-gold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-sage text-sm font-bold bg-sage/10 px-2 py-1 rounded-lg">↗ +8.4%</span>
            </div>
            <h3 class="text-muted text-[11px] font-black uppercase tracking-widest mb-1">Total Revenue</h3>
            <p class="text-2xl font-black text-charcoal tracking-tight">৳{{ number_format($totalRevenue, 0) }}</p>
        </div>

        <!-- Courses Metric -->
        <div class="bg-warm-white rounded-2xl border border-border p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-sage/10 flex items-center justify-center text-sage">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <span class="text-muted text-sm font-bold">Stable</span>
            </div>
            <h3 class="text-muted text-[11px] font-black uppercase tracking-widest mb-1">Active Courses</h3>
            <p class="text-2xl font-black text-charcoal tracking-tight">{{ $activeCourses }}</p>
        </div>

        <!-- Rating Metric -->
        <div class="bg-warm-white rounded-2xl border border-border p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-terracotta/10 flex items-center justify-center text-terracotta">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                </div>
                <div class="flex gap-0.5 text-gold">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
            </div>
            <h3 class="text-muted text-[11px] font-black uppercase tracking-widest mb-1">Avg Rating</h3>
            <p class="text-2xl font-black text-charcoal tracking-tight">{{ number_format($avgRating, 1) }}</p>
        </div>
    </div>
</div>
@endsection