@extends('layouts.instructor')

@section('title', 'Manage Your Courses')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">My Courses</h1>
                    <p class="text-sm text-gray-500 mt-1">An overview of all your published and draft courses.</p>
                </div>
                <a href="{{ route('instructor.course.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md font-bold hover:bg-indigo-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Create a New Course
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($courses as $course)
                <div class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 flex flex-col">
                    <div class="relative h-48 overflow-hidden">
                       @if($course->cover_image)
                            <img src="{{ asset('storage/' . $course->cover_image) }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-indigo-50 to-blue-50 flex items-center justify-center text-indigo-300 group-hover:scale-105 transition-transform duration-500">
                                <svg class="w-16 h-16 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4 flex gap-2">
                            @if($course->is_published)
                                <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-green-700 text-xs font-bold rounded-full shadow-sm">Published</span>
                            @elseif($course->is_submitted)
                                <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-amber-600 text-xs font-bold rounded-full shadow-sm border border-amber-200">In Review</span>
                            @else
                                <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-gray-600 text-xs font-bold rounded-full shadow-sm border border-gray-200">Draft</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="mb-3">
                            <span class="text-xs font-bold text-indigo-600 tracking-wider uppercase">{{ $course->category->name ?? 'Category' }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 line-clamp-2 leading-tight group-hover:text-indigo-600 transition-colors">{{ $course->title }}</h3>
                        
                        <!-- Stats Row -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center text-gray-500 text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span><strong class="text-gray-900">{{ $course->enrollments_count }}</strong> Students</span>
                            </div>
                            
                            @if($course->waitlists_count > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                    📥 {{ $course->waitlists_count }} Waitlisted
                                </span>
                            @else
                                <span class="text-xs text-gray-400 italic">No Waitlist</span>
                            @endif
                        </div>

                        <!-- Footer Actions -->
                        <div class="mt-auto flex items-center justify-between pt-4 border-t border-gray-100">
                            <a href="{{ route('instructor.course.builder', $course->id) }}" class="inline-flex items-center text-xs px-3 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-all font-bold" title="Course Builder">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Manage Course
                            </a>
                            
                            <div class="flex items-center gap-1">
                                @php
                                    $hasStudents = $course->enrollments_count > 0;
                                @endphp

                                @if(!$hasStudents)
                                    <form action="{{ route('instructor.course.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Delete this course?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Course">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                @elseif($course->is_published)
                                    <form action="{{ route('instructor.course.unpublish', $course->id) }}" method="POST" onsubmit="return confirm('Unpublish this course?');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="p-2 text-amber-500 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-colors" title="Unpublish Course">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                        </button>
                                    </form>
                                @else
                                    <span class="p-2 text-gray-300 cursor-not-allowed" title="Archived">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full bg-white p-8 rounded-lg shadow text-center">
                    <p class="text-gray-500">You haven't created any courses yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection