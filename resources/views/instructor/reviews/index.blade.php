@extends('layouts.instructor')

@section('title', 'Course Reviews & Feedback')

@section('content')
<div class="py-12" x-data="{ replyModal: false, activeReview: null, replyText: '' }">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Student Feedback</h1>
                <p class="text-gray-500 font-medium mt-1 text-lg">Manage your reputation and engage with your students.</p>
            </div>
            
            <div class="flex items-center bg-white px-6 py-4 rounded-3xl border border-gray-100 shadow-sm gap-4">
                <div class="text-center border-r border-gray-100 pr-4">
                    <span class="block text-2xl font-black text-indigo-600">{{ number_format($avgRating, 1) }}</span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Global Rating</span>
                </div>
                <div>
                    <div class="flex text-amber-400">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4" fill="{{ $i <= round($avgRating) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Stats -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-2 mb-8 flex flex-wrap items-center gap-2">
            <a href="{{ route('instructor.reviews.index') }}" 
               class="px-5 py-2.5 rounded-2xl text-sm font-bold transition {{ !request('filter') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50' }}">
                All Reviews
            </a>
            <a href="{{ route('instructor.reviews.index', ['filter' => 'unanswered']) }}" 
               class="px-5 py-2.5 rounded-2xl text-sm font-bold transition {{ request('filter') == 'unanswered' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50' }}">
                Unanswered
            </a>
            
            <div class="h-6 w-px bg-gray-100 mx-2"></div>
            
            @for($r = 5; $r >= 1; $r--)
                <a href="{{ route('instructor.reviews.index', ['rating' => $r]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition border {{ request('rating') == $r ? 'bg-amber-50 border-amber-200 text-amber-700' : 'border-transparent text-gray-400 hover:bg-gray-50' }}">
                    {{ $r }} ⭐
                </a>
            @endfor
        </div>

        <!-- Reviews Master List -->
        <div class="space-y-6">
            @forelse($reviews as $review)
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden group hover:border-indigo-200 transition-colors">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Student Info -->
                            <div class="w-full md:w-48 shrink-0">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-500 border border-gray-200">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-gray-900 truncate">{{ $review->user->name }}</h4>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $review->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex text-amber-400 mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5" fill="{{ $i <= $review->rating ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    @endfor
                                </div>
                            </div>

                            <!-- Review Content -->
                            <div class="flex-1 text-left">
                                <div class="mb-4">
                                    <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest flex items-center gap-1.5 mb-1 text-left">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                        {{ $review->course->title }}
                                    </span>
                                    <p class="text-gray-700 font-medium leading-relaxed italic text-left">"{{ $review->comment ?? 'No comment provided.' }}"</p>
                                </div>

                                <!-- Reply Area -->
                                @if($review->instructor_reply)
                                    <div class="bg-indigo-50 rounded-2xl p-5 border border-indigo-100 flex gap-4 text-left">
                                        <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-xs font-bold text-white shrink-0">
                                            Me
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-indigo-900 italic mb-1">My Response</p>
                                            <p class="text-indigo-700 text-sm font-medium">{{ $review->instructor_reply }}</p>
                                            <button @click="activeReview = {{ $review->id }}; replyText = '{{ addslashes($review->instructor_reply) }}'; replyModal = true" class="mt-2 text-[10px] font-bold text-indigo-400 hover:text-indigo-600 uppercase tracking-widest underline underline-offset-4">Edit Reply</button>
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-4 text-left">
                                        <button @click="activeReview = {{ $review->id }}; replyText = ''; replyModal = true" 
                                                class="inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded-xl text-xs font-bold hover:bg-indigo-600 transition shadow-lg shadow-gray-200">
                                            <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                            Reply to Student
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-100">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2 text-center">No Reviews Found</h3>
                    <p class="text-gray-500 font-medium max-w-sm mx-auto text-center">Keep teaching great courses! Once students start leaving feedback, it will appear here.</p>
                </div>
            @endforelse

            <div class="mt-8">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>

    <!-- Reply Modal -->
    <div x-show="replyModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" @click.self="replyModal = false">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-8 overflow-hidden transform transition-all">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-black text-gray-900 tracking-tight">Post Response</h3>
                <button @click="replyModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form :action="`/instructor/reviews/${activeReview}/reply`" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 text-left">Message to student</label>
                    <textarea 
                        name="instructor_reply" 
                        x-model="replyText"
                        class="w-full rounded-2xl border-gray-100 focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 font-medium placeholder-gray-400 min-h-[150px]"
                        placeholder="Thank the student or address their feedback professionally..."
                        required
                    ></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-4 rounded-2xl shadow-xl shadow-indigo-100 transition hover:-translate-y-0.5 active:translate-y-0 text-lg">
                        Post Response
                    </button>
                    <button type="button" @click="replyModal = false" class="px-6 text-gray-500 font-bold hover:text-gray-700 transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
