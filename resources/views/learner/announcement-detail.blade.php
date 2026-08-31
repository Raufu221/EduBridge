<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Breadcrumbs --}}
            <nav class="flex mb-8 text-sm font-medium text-gray-500 dark:text-gray-400">
                <a href="{{ route('dashboard') }}" class="hover:text-orange-500 transition-colors">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('learner.course.viewer', ['course' => $course->id]) }}" class="hover:text-orange-500 transition-colors">{{ $course->title }}</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900 dark:text-white">Announcement</span>
            </nav>

            {{-- Announcement Card --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-3xl overflow-hidden shadow-sm">
                {{-- Header --}}
                <div class="p-8 border-b border-gray-50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-800/50">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-orange-500 text-white flex items-center justify-center shadow-lg shadow-orange-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-black text-orange-500 uppercase tracking-[0.2em]">Course Announcement</span>
                            <h1 class="text-2xl font-black text-gray-900 dark:text-white mt-1 leading-tight">{{ $announcement->title }}</h1>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-50 dark:bg-indigo-500/10 text-indigo-500 flex items-center justify-center font-bold text-sm">
                                {{ substr($announcement->instructor->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white leading-none">{{ $announcement->instructor->name }}</p>
                                <p class="text-[10px] text-gray-500 mt-1">Course Instructor</p>
                            </div>
                        </div>
                        <span class="text-xs font-medium text-gray-400">{{ $announcement->created_at->format('M d, Y') }} ({{ $announcement->created_at->diffForHumans() }})</span>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-8">
                    <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-wrap text-lg">
                        {{ $announcement->content }}
                    </div>

                    {{-- Actions --}}
                    <div class="mt-12 pt-8 border-t border-gray-50 dark:border-gray-700/50 flex items-center justify-between">
                        <a href="{{ route('learner.course.viewer', ['course' => $course->id]) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-2xl font-bold text-sm hover:scale-105 transition-all active:scale-95 shadow-lg shadow-gray-900/10">
                            Go to Class Lessons
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        
                        <a href="{{ route('notifications.index') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                            Back to Notifications
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
