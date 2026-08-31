@extends('layouts.admin')

@section('title', 'Category: ' . $category->name)

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-gray-200 pb-5">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.categories.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Categories
                </a>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                {{ $category->name }}
                <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold border border-gray-200">{{ $courses->total() }} Courses</span>
            </h1>
        </div>
    </div>

    @if($courses->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 text-indigo-300 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">No Courses Found</h3>
            <p class="text-gray-500">There are currently no courses categorized under "{{ $category->name }}".</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($courses as $course)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col group hover:shadow-md transition">
                    <!-- Course Image -->
                    <div class="aspect-video w-full bg-gray-100 relative overflow-hidden">
                        @if($course->cover_image)
                            <img src="{{ asset('storage/' . $course->cover_image) }}" class="w-full h-full object-contain object-center group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-10 h-10 mb-2 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-xs font-semibold uppercase tracking-wider">No Image</span>
                            </div>
                        @endif
                        
                        <!-- Status Badge overlay -->
                        <div class="absolute top-3 right-3 flex flex-col gap-2">
                            @if($course->is_published)
                                <span class="px-2 py-1 bg-emerald-500 text-white text-[10px] font-bold uppercase tracking-wider rounded shadow-sm">Live</span>
                            @else
                                <span class="px-2 py-1 bg-amber-500 text-white text-[10px] font-bold uppercase tracking-wider rounded shadow-sm">Draft</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900 mb-1 line-clamp-2 leading-tight" title="{{ $course->title }}">{{ $course->title }}</h3>
                            <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $course->description ?? 'No description provided.' }}</p>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-[10px] font-bold">
                                    {{ substr($course->instructor->name ?? '?', 0, 1) }}
                                </div>
                                <span class="text-xs font-medium text-gray-600 truncate max-w-[100px]">{{ $course->instructor->name ?? 'Unknown' }}</span>
                            </div>
                            <span class="text-sm font-black text-gray-900">{{ $course->price > 0 ? '$'.$course->price : 'Free' }}</span>
                        </div>
                    </div>

                    <!-- Overlay Actions -->
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex gap-2">
                        <a href="{{ route('admin.course.preview', $course->id) }}" target="_blank" class="flex-1 text-center py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition shadow-sm">
                            Preview Course
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        @if($courses->hasPages())
            <div class="mt-8">
                {{ $courses->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
