@extends('layouts.public')

@section('title', $user->name . ' | Expert Profile | EduBridge')

@section('content')
    <!-- Profile Header -->
    <header class="bg-gradient-to-br from-terracotta-light to-cream pt-40 pb-20 overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="flex flex-col lg:flex-row items-center lg:items-end gap-12">
                <!-- Avatar -->
                <div class="relative group">
                    <div class="w-56 h-56 rounded-[3rem] overflow-hidden border-8 border-white shadow-2xl transition-transform group-hover:rotate-3 duration-500">
                        <img src="{{ $user->profile_pic ? asset('storage/' . $user->profile_pic) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=E65F2B&background=FCEEE8&size=512' }}" 
                             alt="{{ $user->name }}" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-16 h-16 bg-sage rounded-2xl flex items-center justify-center text-white shadow-xl border-4 border-white">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                </div>

                <!-- Info -->
                <div class="flex-1 text-center lg:text-left">
                    <div class="flex flex-wrap justify-center lg:justify-start gap-4 mb-6">
                        <span class="px-4 py-1 bg-terracotta/10 text-terracotta rounded-full text-xs font-black uppercase tracking-widest border border-terracotta/10">Certified Expert</span>
                        <span class="px-4 py-1 bg-sage/10 text-sage rounded-full text-xs font-black uppercase tracking-widest border border-sage/10">Senior Instructor</span>
                    </div>
                    
                    <h1 class="text-5xl lg:text-7xl font-serif font-black text-charcoal mb-4">{{ $user->name }}</h1>
                    <p class="text-xl text-muted-foreground font-medium mb-8 max-w-2xl leading-relaxed">
                        Industry lead with years of experience. Passionate about distilling complex concepts into actionable marketplace skills.
                    </p>

                    <!-- Social Links Placeholders (As requested) -->
                    <div class="flex justify-center lg:justify-start gap-4">
                        <a href="#" class="px-6 py-3 bg-white text-charcoal rounded-xl border border-border flex items-center gap-3 font-bold hover:border-terracotta hover:text-terracotta transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            LinkedIn
                        </a>
                        <a href="#" class="px-6 py-3 bg-white text-charcoal rounded-xl border border-border flex items-center gap-3 font-bold hover:border-terracotta hover:text-terracotta transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                            Twitter
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Decorative bg -->
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-terracotta/5 rounded-full blur-[120px] translate-x-1/3 -translate-y-1/3"></div>
    </header>

    <!-- Bio & Stats -->
    <section class="py-24 max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
            <div class="lg:col-span-2">
                <h3 class="text-3xl font-serif font-black text-charcoal mb-8 italic">About the Instructor</h3>
                <div class="prose prose-lg text-muted-foreground font-medium leading-relaxed max-w-none">
                    @if($user->about_me)
                        {!! nl2br(e($user->about_me)) !!}
                    @else
                        <p>{{ $user->name }} is an expert in their field, dedicated to providing high-quality instruction. They have built their reputation through years of practical experience and a deep commitment to student success.</p>
                        <p class="mt-4">Currently leading advanced workshops and creating comprehensive digital content for EduBridge, helping learners bridge the gap between education and industry.</p>
                    @endif
                </div>
            </div>

            <div class="space-y-8">
                <!-- Instructor Stats -->
                <div class="bg-warm-white p-10 rounded-[2.5rem] border border-border shadow-xl shadow-charcoal/5">
                    <h4 class="text-xs font-black uppercase tracking-widest text-muted-foreground mb-10">Market Statistics</h4>
                    <div class="space-y-8">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-charcoal/60">Active Students</span>
                            <span class="text-2xl font-serif font-black text-charcoal">{{$studentCount}}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-charcoal/60">Course Count</span>
                            <span class="text-2xl font-serif font-black text-charcoal">{{ $courses->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-charcoal/60">Instructor Rating</span>
                            <div class="flex flex-col items-end">
                                <div class="flex gap-1 text-gold mb-1">
                                     @for($i=0; $i<5; $i++)
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    @endfor
                                </div>
                                <span class="text-xs font-black text-charcoal">{{ number_format($avgRating, 1)}}/5</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trusted Badge -->
                <div class="bg-charcoal p-10 rounded-[2.5rem] text-white">
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#FFF]/40 mb-4">Official Verification</p>
                    <p class="text-sm font-bold leading-relaxed">Verified by EduBridge's Academic Board for excellence in curriculum design and student engagement.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Published Courses -->
    <section class="py-24 bg-cream border-t border-border">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-4xl font-serif font-black text-charcoal mb-16 text-center lg:text-left italic">Published Academies</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($courses as $course)
                <div class="flex flex-col group">
                    <a href="{{ route('courses.show', $course) }}" class="relative aspect-video w-full rounded-[2.5rem] overflow-hidden mb-6 block border border-border bg-gray-100 shadow-sm group-hover:shadow-xl transition-all">
                        @if($course->cover_image)
                            <img src="{{ asset('storage/' . $course->cover_image) }}" alt="{{ $course->title }}" class="w-full h-full object-contain object-center group-hover:scale-105 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-terracotta-light to-cream flex items-center justify-center group-hover:scale-105 transition-transform duration-700">
                                <svg class="w-16 h-16 text-terracotta/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                        @endif
                        <div class="absolute top-6 left-6">
                             <span class="px-3 py-1 bg-white/95 backdrop-blur-sm text-charcoal rounded-full text-[10px] font-black shadow-sm uppercase tracking-widest border border-border">
                                {{ $course->category->name ?? 'Course' }}
                            </span>
                        </div>
                    </a>
                    
                    <div class="px-2">
                        <div class="flex items-center gap-2 mb-3">
                             <div class="flex text-gold">
                                @for($i=0; $i<5; $i++)
                                    <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endfor
                            </div>
                            <span class="text-[10px] font-black text-muted-foreground uppercase tracking-widest">({{ $course->reviews_count }} reviews)</span>
                        </div>
                        <a href="{{ route('courses.show', $course) }}" class="text-2xl font-bold text-charcoal mb-4 block hover:text-terracotta transition-colors line-clamp-2">
                            {{ $course->title }}
                        </a>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-black text-charcoal">{{ $course->price == 0 ? 'Free' : '৳' . number_format($course->price, 0) }}</span>
                            <span class="text-[10px] font-black text-muted-foreground uppercase tracking-widest italic">All Levels</span>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-warm-white rounded-[3rem] border border-dashed border-border">
                        <p class="text-muted-foreground italic">No published academies currently available. Check back soon!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

@endsection
