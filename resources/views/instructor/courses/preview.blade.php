@extends('layouts.instructor')

@section('title', 'Course Preview')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Preview Banner -->
        <div class="mb-4 bg-amber-100 text-amber-800 p-4 rounded-lg flex justify-between items-center shadow-sm border border-amber-200">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                <span class="font-bold">Instructor Preview Mode</span> You are viewing this course exactly as a student would see it.
            </div>
            <a href="{{ route('instructor.course.builder', $course->id) }}" class="text-sm font-semibold hover:underline">Back to Builder</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Curriculum -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-100">
                        <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-full mb-4">{{ $course->category->name ?? 'Category' }}</span>
                        <h1 class="text-3xl font-extrabold text-gray-900 mb-4">{{ $course->title }}</h1>
                        <p class="text-gray-600 mb-6">{{ $course->description }}</p>

                        @if(is_array($course->what_you_will_learn) && count($course->what_you_will_learn) > 0)
                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 mb-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">What you'll learn</h3>
                            <ul class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700">
                                @foreach($course->what_you_will_learn as $item)
                                    <li class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        {{ $item }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>

                    <!-- Syllabus -->
                    <div class="p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Course Content</h2>
                        
                        <div class="space-y-4">
                            @forelse($course->modules as $module)
                                <div class="border border-gray-200 rounded-xl overflow-hidden hover:border-indigo-200 transition">
                                    <div class="bg-gray-50 px-6 py-4 flex justify-between items-center cursor-pointer">
                                        <h3 class="font-bold text-gray-900 text-lg">Module {{ $loop->iteration }}: {{ $module->title }}</h3>
                                        <span class="text-sm text-gray-500 font-medium">{{ $module->lessons->count() }} lessons</span>
                                    </div>
                                    <div class="divide-y divide-gray-100">
                                        @foreach($module->lessons as $lesson)
                                            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                                                <div class="flex items-center gap-3">
                                                    @if($lesson->type == 'video')
                                                        <svg class="w-5 h-5 text-indigo-500" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                    @else
                                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    @endif
                                                    <span class="text-gray-700 font-medium">{{ $lesson->title }}</span>
                                                </div>
                                                @if($lesson->duration)
                                                    <span class="text-sm text-gray-500">{{ $lesson->duration }} min</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 italic">No modules have been added to this course yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    @if($course->cover_image)
                        <img src="{{ asset('storage/' . $course->cover_image) }}" alt="Cover" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-indigo-100 to-indigo-50 flex flex-col items-center justify-center text-indigo-300">
                            <svg class="w-16 h-16 opacity-50 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-sm font-medium opacity-50">No Cover Image</span>
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <div class="text-3xl font-bold text-gray-900 mb-6">
                            @if($course->price && $course->price > 0)
                                ৳{{ number_format($course->price, 0) }}
                            @else
                                Free
                            @endif
                        </div>
                        
                        @php
                            $isFull = $course->max_students && $course->enrollments_count >= $course->max_students;
                            $remainingSeats = $course->max_students ? $course->max_students - $course->enrollments_count : null;
                            $fillPercent = $course->max_students ? ($course->enrollments_count / $course->max_students) * 100 : 0;
                        @endphp

                        @if($course->max_students)
                            <div class="mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <div class="flex justify-between items-end mb-2">
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Enrollment Status</span>
                                    <span class="text-xs font-bold {{ $isFull ? 'text-red-500' : 'text-indigo-600' }}">
                                        @if($isFull)
                                            Sold Out ({{ $course->enrollments_count }} / {{ $course->max_students }})
                                        @else
                                            {{ $course->enrollments_count }} / {{ $course->max_students }} Seats Filled
                                        @endif
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mb-2 overflow-hidden">
                                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-full rounded-full transition-all duration-1000 shadow-sm" style="width: {{ $fillPercent }}%"></div>
                                </div>
                                @if(!$isFull && $remainingSeats <= 10)
                                    <div class="flex items-center gap-2 text-[10px] font-bold text-amber-600 animate-pulse">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path></svg>
                                        CRITICAL SCARCITY: {{ $remainingSeats }} {{ Str::plural('SEAT', $remainingSeats) }} REMAINING
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="mb-6 flex items-center justify-between py-2.5 px-4 bg-indigo-50 border border-indigo-100 rounded-xl">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                                    <span class="text-xs font-bold text-indigo-700">Current Interest</span>
                                </div>
                                <span class="text-xs font-bold text-indigo-600">{{ $course->enrollments_count }} Peers Enrolled</span>
                            </div>
                        @endif

                        <button disabled class="w-full py-3 px-4 bg-indigo-600 text-white rounded-xl font-bold text-lg hover:bg-indigo-700 transition opacity-70 cursor-not-allowed mb-4 shadow-sm">
                            Enroll Now (Preview)
                        </button>
                        
                        <p class="text-sm text-gray-500 text-center mb-6 border-b border-gray-100 pb-6">30-Day Money-Back Guarantee</p>
                        
                        <div class="space-y-4 text-sm text-gray-700">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                <span><strong>Level:</strong> {{ ucfirst($course->level) }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <span><strong>Instructor:</strong> {{ $course->instructor->name ?? 'You' }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                <span><strong>Content:</strong> {{ $course->modules->count() }} modules</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
