@extends('layouts.public')

@section('title', 'Learner Reviews | Wall of Love | EduBridge')

@section('content')
<div class="pt-40 pb-32 bg-cream min-h-screen">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center pb-20">
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/50 backdrop-blur-md rounded-full border border-white mb-8 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-gold"></span>
            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-charcoal">The Wall of Love</span>
        </div>
        <h1 class="text-5xl lg:text-7xl font-serif font-black text-charcoal leading-tight mb-6">Learner Experiences</h1>
        <p class="text-xl text-muted-foreground font-medium max-w-2xl mx-auto">
            Honest feedback from our global community of ambitious professionals. Excellence is our standard.
        </p>
    </div>

    <div class="max-w-5xl mx-auto px-6 lg:px-8">
        <div class="space-y-12">
            @forelse($reviews as $review)
            <div class="bg-warm-white p-12 rounded-[3.5rem] border border-border shadow-2xl shadow-charcoal/[0.03] transition-all hover:shadow-charcoal/[0.06] group relative">
                <div class="absolute -top-6 left-12">
                     <div class="flex gap-1 p-3 bg-white rounded-2xl border border-border shadow-sm">
                        @for($i=0; $i<$review->rating; $i++)
                            <svg class="w-4 h-4 text-gold fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        @endfor
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-12 items-start">
                    <div class="flex-1">
                        <p class="text-2xl text-charcoal font-medium leading-[1.7] italic mb-10">
                            "{{ $review->comment }}"
                        </p>
                        
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-cream rounded-[1.5rem] flex items-center justify-center font-black text-charcoal border border-border overflow-hidden shrink-0">
                                @if($review->user->profile_pic)
                                    <img src="{{ asset('storage/' . $review->user->profile_pic) }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($review->user->name, 0, 1) }}
                                @endif
                            </div>
                            <div>
                                <h5 class="text-xl font-black text-charcoal mb-1">{{ $review->user->name }}</h5>
                                <a href="{{ route('courses.show', $review->course) }}" class="text-xs font-black uppercase tracking-widest text-terracotta hover:underline">
                                    Course: {{ $review->course->title }}
                                </a>
                            </div>
                        </div>
                    </div>

                    @if($review->instructor_reply)
                    <div class="w-full md:w-72 bg-cream/50 rounded-3xl p-8 border border-border/50">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-2 h-2 rounded-full bg-sage"></span>
                            <span class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Instructor Response</span>
                        </div>
                        <p class="text-sm font-medium text-charcoal/80 leading-relaxed italic">
                            "{{ $review->instructor_reply }}"
                        </p>
                        <div class="mt-4 flex items-center gap-2">
                             <div class="w-6 h-6 rounded-lg bg-charcoal flex items-center justify-center font-black text-white text-[8px]">
                                {{ substr($review->course->instructor->name ?? 'I', 0, 1) }}
                             </div>
                             <span class="text-[10px] font-bold text-charcoal/60">{{ $review->course->instructor->name ?? 'Instructor' }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="py-24 text-center bg-warm-white rounded-[3rem] border border-dashed border-border flex flex-col items-center">
                <div class="w-16 h-16 bg-muted rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"></path></svg>
                </div>
                <h3 class="text-xl font-black text-charcoal mb-2">No reviews found yet</h3>
                <p class="text-muted-foreground font-medium">Be the first to share your learning experience!</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-24 flex justify-center">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection
