@extends('layouts.instructor')

@section('title', 'Course Announcements')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Course Announcements</h1>
        <p class="text-sm text-gray-500 mt-1">Broadcast important updates to your students</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Post New Announcement Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                Post New Announcement
            </h3>
            
            <form action="{{ route('instructor.announcements.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Target Course</label>
                        <select name="course_id" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-terracotta transition">
                            <option value="" disabled selected>-- Select Course --</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Announcement Title</label>
                        <input type="text" name="title" required placeholder="e.g., Assignment #2 Deadline Extended" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-terracotta transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Content Message</label>
                        <textarea name="content" rows="5" required placeholder="Write your message here..." class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-terracotta transition"></textarea>
                    </div>

                    <div class="bg-amber-50 border border-amber-100 rounded-xl p-3 text-[11px] text-amber-700 leading-relaxed">
                        <p class="font-bold flex items-center gap-1 mb-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                            Notification Notice:
                        </p>
                        This will send an email and a dashboard notification to all enrolled students immediately.
                    </div>

                    <button type="submit" class="w-full py-3 bg-terracotta text-white font-bold rounded-xl shadow-lg shadow-terracotta/30 hover:opacity-90 transition transform hover:-translate-y-0.5">
                        Broadcast Announcement
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Announcement History -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-gray-50/30">
                <h3 class="font-bold text-gray-900">Announcement History</h3>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($announcements as $announcement)
                    <div class="p-6 hover:bg-gray-50/50 transition">
                        <div class="flex items-start justify-between gap-4 mb-2">
                            <div>
                                <h4 class="font-bold text-gray-900 leading-tight">{{ $announcement->title }}</h4>
                                <p class="text-xs font-bold text-terracotta mt-1 uppercase tracking-widest">
                                    {{ $announcement->course ? $announcement->course->title : 'Platform Wide' }}
                                </p>
                            </div>
                            <span class="text-[10px] font-medium text-gray-400 whitespace-nowrap">{{ $announcement->created_at->format('M d, Y') }}</span>
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed line-clamp-2">
                            {{ $announcement->content }}
                        </p>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4 text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                        </div>
                        <p class="text-gray-500 font-medium">No announcements posted yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
