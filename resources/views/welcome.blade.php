@extends('layouts.public')

@section('title', 'EduBridge | Excellence in Peer-to-Peer Learning')

@section('content')
    <!-- Hero Section -->
    <section class="relative pt-6 pb-20 overflow-hidden">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-10">
            <!-- Containerized Hero -->
            <div class="relative bg-charcoal rounded-[3.5rem] overflow-hidden min-h-[650px] flex items-center shadow-2xl shadow-charcoal/20">
                <!-- Background Image & Overlay -->
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=2000&q=90" 
                         alt="Collaboration" 
                         class="w-full h-full object-cover opacity-30 scale-105 animate-slow-zoom">
                    <div class="absolute inset-0 bg-gradient-to-r from-charcoal via-charcoal/80 to-transparent"></div>
                </div>

                <!-- Hero Content -->
                <div class="relative z-10 max-w-[1400px] mx-auto px-8 lg:px-16 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center w-full">
                    <div class="animate-fade-in-up">
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20 mb-8 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-terracotta animate-pulse"></span>
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-white">Earning the world's trust</span>
                        </div>
                        
                        <h1 class="text-6xl lg:text-8xl font-serif font-black text-white leading-[1.05] mb-8">
                            Learn from the <br>
                            <span class="text-terracotta italic underline underline-offset-8 decoration-white/20">Absolute Best.</span>
                        </h1>
                        
                        <p class="text-xl text-white/70 font-medium leading-relaxed max-w-xl mb-12">
                            EduBridge connects ambitious learners with industry veterans. Skip the theory, learn the craft directly from those who built it.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-5">
                            <a href="{{ route('courses.index') }}" class="px-10 py-5 bg-terracotta text-white rounded-2xl font-black text-lg shadow-xl shadow-terracotta/20 hover:shadow-terracotta/40 transition-all transform hover:-translate-y-1 text-center">
                                Browse Catalog
                            </a>
                            <a href="{{ route('teach.index') }}" class="px-10 py-5 bg-white/5 backdrop-blur-md border border-white/10 text-white rounded-2xl font-black text-lg hover:bg-white/10 transition-all text-center">
                                Become a Teacher
                            </a>
                        </div>
                    </div>

                    <!-- Right Side: Decorative Badge -->
                    <div class="hidden lg:flex justify-end animate-fade-in-right">
                        <div class="bg-white/5 backdrop-blur-xl border border-white/10 p-10 rounded-[3rem] max-w-sm transform rotate-3 hover:rotate-0 transition-transform duration-700">
                            <div class="w-20 h-20 bg-terracotta rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-terracotta/20">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <h3 class="text-2xl font-black text-white mb-4 leading-tight">Master Real-World Skills Today.</h3>
                            <p class="text-white/60 text-sm font-medium leading-relaxed">Access 1,200+ courses curated by top 1% industry experts across Design, Code, and Business.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Animations -->
        <style>
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes fadeInRight {
                from { opacity: 0; transform: translateX(30px); }
                to { opacity: 1; transform: translateX(0); }
            }
            @keyframes slowZoom {
                from { transform: scale(1.05); }
                to { transform: scale(1.15); }
            }
            .animate-fade-in-up { animation: fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
            .animate-fade-in-right { animation: fadeInRight 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
            .animate-slow-zoom { animation: slowZoom 20s linear infinite alternate; }
        </style>
    </section>

    <!-- Floating Stats Section -->
    <div class="max-w-[1440px] mx-auto px-4 lg:px-10 relative z-20 mt-[-48px]">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-6">
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-charcoal/5 group hover:border-terracotta transition-colors text-center">
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Active Students</p>
                <h3 class="text-4xl font-serif font-black text-charcoal">50k+</h3>
            </div>
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-charcoal/5 group hover:border-terracotta transition-colors text-center">
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Industry Experts</p>
                <h3 class="text-4xl font-serif font-black text-charcoal">250+</h3>
            </div>
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-charcoal/5 group hover:border-terracotta transition-colors text-center">
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Premium Courses</p>
                <h3 class="text-4xl font-serif font-black text-charcoal">1,200+</h3>
            </div>
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-charcoal/5 group hover:border-terracotta transition-colors text-center">
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Satisfaction</p>
                <h3 class="text-4xl font-serif font-black text-charcoal">4.8/5</h3>
            </div>
        </div>
    </div>

    <!-- Trending Academies Section -->
    <section class="py-24 bg-white">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-10">
            <div class="flex flex-col md:flex-row justify-between items-end gap-8 mb-16">
                <div class="max-w-2xl">
                    <h2 class="text-xs font-black uppercase tracking-[0.4em] text-terracotta mb-4">Top Rated Academies</h2>
                    <h3 class="text-4xl md:text-5xl font-serif font-black text-charcoal">The most in-demand skills.</h3>
                </div>
                <a href="{{ route('courses.index') }}" class="text-sm font-black text-terracotta uppercase tracking-widest hover:underline whitespace-nowrap mb-2">Explore All &rarr;</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($featuredCourses as $course)
                <div class="group bg-white rounded-[2rem] border border-gray-100 shadow-sm p-6 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl cursor-pointer flex flex-col h-full">
                    <!-- Thumbnail Container -->
                    <a href="{{ route('courses.show', $course) }}" class="relative aspect-video w-full rounded-2xl overflow-hidden mb-6 block bg-gray-50 border border-gray-50">
                        @if($course->cover_image)
                            <img src="{{ asset('storage/' . $course->cover_image) }}" alt="{{ $course->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-terracotta-light/30 to-cream flex items-center justify-center">
                                <svg class="w-12 h-12 text-terracotta/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                        @endif
                    </a>
                    
                    <div class="flex-1 flex flex-col px-1">
                        <!-- Rating & Social Proof -->
                        <div class="flex items-center gap-2 mb-4">
                             <div class="flex text-gold">
                                @for($i=0; $i<5; $i++)
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endfor
                            </div>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">({{ number_format($course->reviews_count) }} Reviews)</span>
                        </div>

                        <!-- Title -->
                        <a href="{{ route('courses.show', $course) }}" class="text-xl font-bold text-charcoal mb-6 block hover:text-terracotta transition-colors line-clamp-2 leading-tight">
                            {{ $course->title }}
                        </a>

                        <!-- Instructor & Price Row -->
                        <div class="mt-auto pt-6 border-t border-gray-50 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center font-black text-terracotta text-xs overflow-hidden shrink-0">
                                    @if($course->instructor->profile_pic)
                                        <img src="{{ asset('storage/' . $course->instructor->profile_pic) }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($course->instructor->name ?? 'I', 0, 1) }}
                                    @endif
                                </div>
                                <span class="text-xs font-bold text-charcoal">{{ $course->instructor->name ?? 'Expert' }}</span>
                            </div>
                            <span class="text-lg font-black text-charcoal">{{ $course->price == 0 ? 'Free' : '৳' . number_format($course->price, 0) }}</span>
                        </div>
                    </div>
                </div>
                @empty
                    <p class="col-span-full text-center py-20 text-muted-foreground italic">New academies coming soon!</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Meet the Instructors Section -->
    <section class="py-24 bg-gray-50/50 relative overflow-hidden">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-10 relative z-10">
           <div class="flex flex-col md:flex-row justify-between items-end gap-8 mb-16 text-left">
             <div class="max-w-2xl">
                 <h2 class="text-xs font-black uppercase tracking-[0.4em] text-terracotta mb-4">Expert Faculty</h2>
                 <h3 class="text-4xl md:text-5xl font-serif font-black text-charcoal leading-tight">Learn from the best minds in the industry.</h3>
             </div>
             <a href="{{ route('instructors.index') }}" class="text-sm font-black text-terracotta uppercase tracking-widest hover:underline whitespace-nowrap mb-2">Explore All Experts &rarr;</a>
           </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">
                @foreach($featuredInstructors as $instructor)
                <div class="group relative flex flex-col items-center">
                    <a href="{{ route('instructor.profile', $instructor) }}" class="relative mb-8">
                        <!-- Glowing Ring -->
                        <div class="absolute inset-0 bg-terracotta rounded-full blur-2xl opacity-0 group-hover:opacity-20 transition-opacity duration-500"></div>
                        <!-- Profile Image -->
                        <div class="relative w-44 h-44 rounded-full border-4 border-white shadow-2xl overflow-hidden group-hover:scale-105 transition-transform duration-500 ring-1 ring-gray-100">
                            <img src="{{ $instructor->profile_pic ? asset('storage/' . $instructor->profile_pic) : 'https://ui-avatars.com/api/?name='.urlencode($instructor->name).'&color=E65F2B&background=FCEEE8&size=512' }}" 
                                 alt="{{ $instructor->name }}" 
                                 class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                        </div>
                    </a>
                    
                    <div class="text-center">
                        <h4 class="text-2xl font-bold text-charcoal mb-1">{{ $instructor->name }}</h4>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $instructor->expertise ?? 'Lead Mentor' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-24 bg-charcoal relative overflow-hidden">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-10 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-20">
                <div>
                    <h2 class="text-xs font-black uppercase tracking-[0.4em] text-terracotta mb-6">Testimonials</h2>
                    <h3 class="text-4xl md:text-5xl font-serif font-black text-white leading-tight">Wall of Love.</h3>
                </div>
                <div class="flex flex-col lg:items-end">
        <p class="text-xl text-white/50 font-medium leading-relaxed mb-6 lg:text-right">
            Join over 50,000+ ambitious learners who have accelerated their careers with EduBridge.
        </p>
        <a href="{{ route('reviews.index') }}" class="text-sm font-black text-terracotta uppercase tracking-widest hover:underline whitespace-nowrap">Read All Reviews &rarr;</a>
    </div>
</div>


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @forelse($reviews as $review)
                <div class="bg-white/5 backdrop-blur-md border border-white/10 p-10 rounded-[2.5rem] flex flex-col h-full hover:bg-white/10 transition-all group">
                    <div class="flex gap-1 mb-8">
                        @for($i=0; $i<$review->rating; $i++)
                            <svg class="w-4 h-4 text-terracotta fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        @endfor
                    </div>
                    <p class="text-xl text-white font-medium leading-relaxed mb-10">
                        "{{ $review->comment }}"
                    </p>
                    <div class="flex items-center gap-4 mt-auto">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/10 overflow-hidden shrink-0">
                             @if($review->user->profile_pic)
                                 <img src="{{ asset('storage/' . $review->user->profile_pic) }}" class="w-full h-full object-cover">
                             @else
                                 <div class="w-full h-full flex items-center justify-center text-white font-black text-xs bg-terracotta/20">
                                     {{ substr($review->user->name, 0, 1) }}
                                 </div>
                             @endif
                        </div>
                        <div>
                            <h5 class="font-bold text-white flex items-center gap-2">
                                {{ $review->user->name }}
                            </h5>
                            <p class="text-[10px] font-black uppercase tracking-widest text-white/40">{{ $review->course->title }}</p>
                        </div>
                    </div>
                </div>
                @empty
                    @php
                        $placeholders = [
                            ['name' => 'Alex Johnson', 'comment' => 'The most practical course I have ever taken. I landed a job at Google just 3 months after finishing.', 'course' => 'Full Stack Web Development'],
                            ['name' => 'Sarah Williams', 'comment' => 'The direct mentorship from industry experts is what sets EduBridge apart.', 'course' => 'UI/UX Design Masterclass'],
                            ['name' => 'David Chen', 'comment' => 'Finally, a platform that focuses on real-world skills instead of just theory.', 'course' => 'Data Science Essentials']
                        ];
                    @endphp
                    @foreach($placeholders as $p)
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-10 rounded-[2.5rem] flex flex-col h-full hover:bg-white/10 transition-all group">
                        <div class="flex gap-1 mb-8">
                            @for($i=0; $i<5; $i++)
                                <svg class="w-4 h-4 text-terracotta fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endfor
                        </div>
                        <p class="text-xl text-white font-medium leading-relaxed mb-10">
                            "{{ $p['comment'] }}"
                        </p>
                        <div class="flex items-center gap-4 mt-auto">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center text-white font-black text-xs">
                                 {{ substr($p['name'], 0, 1) }}
                            </div>
                            <div>
                                <h5 class="font-bold text-white flex items-center gap-2">
                                    {{ $p['name'] }}
                                </h5>
                                <p class="text-[10px] font-black uppercase tracking-widest text-white/40">{{ $p['course'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-20 bg-white">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-10">
            <div class="bg-charcoal rounded-[3.5rem] p-12 md:p-20 text-center overflow-hidden relative group shadow-2xl shadow-charcoal/20">
                <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-terracotta/10 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/2 group-hover:bg-terracotta/20 transition-all duration-700"></div>
                
                <h2 class="text-4xl md:text-7xl font-serif font-black text-white leading-tight mb-8 relative z-10">
                    Ready to <span class="text-terracotta">Advance</span> <br> Your Future?
                </h2>
                <p class="text-xl text-white/50 font-medium mb-12 max-w-2xl mx-auto relative z-10">Join thousands of professionals mastering new crafts daily.</p>
                
                <div class="flex flex-col sm:flex-row justify-center gap-6 relative z-10">
                    <a href="{{ route('register') }}" class="px-12 py-5 bg-terracotta text-white rounded-2xl font-black text-xl hover:shadow-2xl hover:shadow-terracotta/20 transition-all transform hover:-translate-y-1">
                        Get Started Free
                    </a>
                    <a href="{{ route('teach.index') }}" class="px-12 py-5 bg-white/5 border border-white/10 text-white rounded-2xl font-black text-xl hover:bg-white/10 transition-all">
                        Apply to Teach
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
