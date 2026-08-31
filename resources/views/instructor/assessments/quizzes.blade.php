@extends('layouts.instructor')

@section('title', 'Quizzes Dashboard')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Quizzes</h1>
        <p class="text-sm text-gray-500 mt-1">Create and manage your course quizzes</p>
    </div>
    <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="px-4 py-2.5 bg-[#5A4BFF] text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Create Quiz
    </button>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-md shadow-indigo-500/5 p-6 flex items-center gap-4 transition-all hover:shadow-lg hover:border-indigo-100">
        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-[#5A4BFF] flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Total</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ collect($quizzes)->count() }}</h3>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-md shadow-indigo-500/5 p-6 flex items-center gap-4 transition-all hover:shadow-lg hover:border-blue-100">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Attempts</p>
            <h3 class="text-2xl font-bold text-gray-900">0</h3>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-md shadow-indigo-500/5 p-6 flex items-center gap-4 transition-all hover:shadow-lg hover:border-emerald-100">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Avg Score</p>
            <h3 class="text-2xl font-bold text-gray-900">0%</h3>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-md shadow-indigo-500/5 p-6 flex items-center gap-4 transition-all hover:shadow-lg hover:border-amber-100">
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Needs Review</p>
            <h3 class="text-2xl font-bold text-gray-900">0</h3>
        </div>
    </div>
</div>

<!-- Table & Filters -->
<div x-data="{ searchQuery: '', selectedCourse: '' }" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-12 shadow-indigo-500/5">
    <div class="p-5 border-b border-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gray-50/30">
        <h3 class="font-bold text-gray-900">All Quizzes</h3>
        <div class="flex items-center gap-3">
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" x-model="searchQuery" placeholder="Search quizzes..." class="pl-9 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-[#5A4BFF] focus:ring-1 focus:ring-[#5A4BFF] transition w-full md:w-64">
            </div>
            <select x-model="selectedCourse" class="px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-600 focus:outline-none focus:border-[#5A4BFF] transition appearance-none cursor-pointer hidden md:block">
                <option value="">All Courses</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                @endforeach
            </select>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[700px]">
            <thead>
                <tr class="bg-white border-b border-gray-100 text-[11px] text-gray-400 font-bold uppercase tracking-wider">
                    <th class="p-4 pl-6">Quiz Title (Lesson)</th>
                    <th class="p-4">Associated Course</th>
                    <th class="p-4 text-center">Time Limit</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 pr-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 bg-white">
                @forelse($quizzes as $quiz)
                    <tr x-show="(searchQuery === '' || '{{ strtolower($quiz->lesson->title ?? '') }}'.includes(searchQuery.toLowerCase())) && (selectedCourse === '' || '{{ $quiz->lesson->module->course->id ?? '' }}' === selectedCourse)" class="group hover:bg-[#F9F9FF] transition-colors relative">
                        <td class="p-4 pl-6 font-semibold text-gray-900">
                            <!-- Left highlight border on hover -->
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#5A4BFF] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            {{ $quiz->lesson->title ?? 'Unknown Lesson' }}
                        </td>
                        <td class="p-4 text-sm text-gray-500 font-medium">{{ $quiz->lesson->module->course->title ?? 'N/A' }}</td>
                        <td class="p-4 text-sm font-bold text-gray-900 text-center">{{ $quiz->time_limit_minutes }} min</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-lg border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Active
                            </span>
                        </td>
                        <td class="p-4 pr-6 text-right relative">
                            <!-- Action Dropdown Trigger -->
                            <div class="flex justify-end relative" x-data="{ open: false }" @click.outside="open = false">
                                <button @click="open = !open" class="text-gray-400 hover:text-[#5A4BFF] p-1.5 rounded-lg hover:bg-white border border-transparent hover:border-gray-200 transition shadow-sm hover:shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                </button>
                                <!-- Dropdown Menu -->
                                <div x-show="open" class="absolute right-0 top-10 w-48 bg-white rounded-xl shadow-lg shadow-indigo-500/10 border border-gray-100 z-10 py-1" style="display: none;" x-transition.opacity.duration.200ms>
                                    @if($quiz->lesson && $quiz->lesson->module && $quiz->lesson->module->course)
                                        <a href="{{ route('instructor.quiz.build', [$quiz->lesson->module->course->id, $quiz->lesson->id]) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-[#5A4BFF] transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Edit Quiz
                                        </a>
                                    @endif
                                    <a href="#" class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-[#5A4BFF] transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        View Attempts
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-16 text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-indigo-50/50 text-[#5A4BFF] mb-5 shadow-inner">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h4 class="text-xl font-bold text-gray-900 mb-2">No Quizzes Yet</h4>
                            <p class="text-gray-500 text-sm mb-8 max-w-sm mx-auto leading-relaxed">Engage your students by testing their comprehension with interactive multiple-choice quizzes.</p>
                            <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="px-6 py-3 bg-[#5A4BFF] text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-xl shadow-indigo-500/30 hover:-translate-y-1">
                                Create First Quiz
                            </button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Smart Modal for Creating Quiz -->
<div id="createModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center transition-opacity" style="z-index: 100;">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all scale-100">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#5A4BFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create New Quiz
            </h3>
            <button onclick="document.getElementById('createModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 hover:bg-gray-100 p-2 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-500 mb-6 leading-relaxed">Select the course you want to add a quiz to. You will be redirected to its powerful curriculum builder.</p>
            
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-800 mb-2">Target Course</label>
                <div class="relative">
                    <select id="courseSelect" class="w-full pl-4 pr-10 py-3.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#5A4BFF] focus:ring-2 focus:ring-indigo-100 transition cursor-pointer font-medium text-gray-700 appearance-none shadow-sm">
                        <option value="" disabled selected>-- Choose a Course --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                    <svg class="w-5 h-5 absolute right-4 top-3.5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-8 pt-4 border-t border-gray-100">
                <button onclick="document.getElementById('createModal').classList.add('hidden')" class="px-5 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-100 rounded-xl transition">Cancel</button>
                <button onclick="if(document.getElementById('courseSelect').value){ window.location.href='/instructor/course/' + document.getElementById('courseSelect').value + '/builder'; }" class="px-6 py-2.5 bg-[#5A4BFF] text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30 flex items-center gap-2">
                    Continue to Builder
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Required for Alpine x-data in table actions -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection
