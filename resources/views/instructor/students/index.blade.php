@extends('layouts.instructor')

@section('title', 'Student Roster')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">My Students</h1>
        <p class="text-sm text-gray-500 mt-1">Track progress, manage enrollments, and support your learners.</p>
    </div>
</div>

<!-- KPI Ribbon -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex flex-col justify-center">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-8 h-8 rounded-lg bg-[#5A4BFF]/10 text-[#5A4BFF] flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Students</p>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $totalStudents }}</h3>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex flex-col justify-center">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Course Enrollments</p>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $totalEnrollments }}</h3>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex flex-col justify-center">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Avg Completion</p>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($avgCompletion, 1) }}%</h3>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex flex-col justify-center bg-gradient-to-br from-[#5A4BFF] to-indigo-700 text-white relative overflow-hidden">
        <svg class="absolute -bottom-4 -right-4 w-24 h-24 text-white/10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21a9 9 0 100-18 9 9 0 000 18zm0-2a7 7 0 110-14 7 7 0 010 14zm0-11a2 2 0 100 4 2 2 0 000-4z"/></svg>
        <div class="relative z-10">
            <p class="text-xs font-semibold text-indigo-100 uppercase tracking-wider mb-2">Active This Week</p>
            <h3 class="text-2xl font-bold text-white">100%</h3>
        </div>
    </div>
</div>

<!-- Control Bar (Filter & Search) -->
<div x-data="{ searchQuery: '', expandedRow: null }">
    <form method="GET" action="{{ route('instructor.students.index') }}" class="mb-6 flex flex-wrap gap-4 items-center justify-between bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex gap-4 flex-1">
            <div class="relative w-full max-w-sm">
                <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" x-model="searchQuery" placeholder="Search by name or email..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#5A4BFF]/20 focus:border-[#5A4BFF]">
        </div>
        <select name="course_id" onchange="this.form.submit()" class="border border-gray-200 rounded-lg py-2 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#5A4BFF]/20 focus:border-[#5A4BFF] bg-white">
            <option value="all">All Courses</option>
            @foreach($ownedCourses as $course)
                <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
            @endforeach
        </select>
    </div>
</form>

<!-- Master Data Table (Accordion Design) -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden shadow-indigo-500/5">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 font-bold uppercase tracking-wider">
                    <th class="p-4 rounded-tl-lg font-semibold min-w-[250px]">Student Name</th>
                    <th class="p-4 font-semibold text-center">Enrolled Courses</th>
                    <th class="p-4 font-semibold">Average Progress</th>
                    <th class="p-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($students as $student)
                    @php
                        $avgStudentProgress = $student->enrollments->avg('progress_percent') ?? 0;
                    @endphp
                    <!-- Main Row -->
                    <tr x-show="searchQuery === '' || '{{ strtolower($student->name) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($student->email) }}'.includes(searchQuery.toLowerCase())" class="hover:bg-gray-50 transition cursor-pointer group" @click="expandedRow = expandedRow === {{ $student->id }} ? null : {{ $student->id }}">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=random" class="w-10 h-10 rounded-full outline outline-2 outline-white shadow-sm">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $student->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $student->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-50 text-[#5A4BFF] text-xs font-bold ring-1 ring-indigo-100">
                                {{ $student->enrollments->count() }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="w-full max-w-[200px]">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-xs font-bold text-gray-700">{{ number_format($avgStudentProgress, 0) }}% Avg</span>
                                    @if($avgStudentProgress == 100)
                                        <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            Completed
                                        </span>
                                    @endif
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden border border-gray-200 shadow-inner">
                                    <div class="h-2 rounded-full {{ $avgStudentProgress == 100 ? 'bg-emerald-500' : 'bg-[#5A4BFF]' }}" style="width: {{ $avgStudentProgress }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex flex-row justify-end items-center gap-3 relative" @click.stop>
                                <!-- Accordion indicator -->
                                <button class="p-1 px-3 text-xs bg-white border border-gray-200 hover:border-[#5A4BFF] hover:text-[#5A4BFF] text-gray-500 rounded-md transition flex items-center gap-1 focus:outline-none" @click="expandedRow = expandedRow === {{ $student->id }} ? null : {{ $student->id }}">
                                    <span x-text="expandedRow === {{ $student->id }} ? 'Hide Details' : 'View Details'"></span>
                                    <svg class="w-3 h-3 transform transition-transform duration-200" :class="expandedRow === {{ $student->id }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                            
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Expanded Row (Accordion Content) -->
                    <tr x-show="expandedRow === {{ $student->id }} && (searchQuery === '' || '{{ strtolower($student->name) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($student->email) }}'.includes(searchQuery.toLowerCase()))" style="display: none;" class="bg-[#F8F9FA] border-b border-gray-200">
                        <td colspan="4" class="p-0">
                            <!-- Inner Table -->
                            <div class="p-6 border-l-4 border-[#5A4BFF]">
                                <h4 class="text-sm font-bold text-gray-900 mb-4 px-2">Course Enrollments Map</h4>
                                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                                    <table class="w-full text-left">
                                        <thead>
                                            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 font-bold uppercase">
                                                <th class="p-3 pl-4">Course Title</th>
                                                <th class="p-3">Specific Progress</th>
                                                <th class="p-3">Enrolled Date</th>
                                                <th class="p-3 text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach($student->enrollments as $enrollment)
                                                <tr class="hover:bg-gray-50 transition">
                                                    <td class="p-3 pl-4">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-10 h-8 bg-gray-200 rounded border border-gray-300 overflow-hidden">
                                                                @if($enrollment->course->cover_image)
                                                                    <img src="{{ asset('storage/' . $enrollment->course->cover_image) }}" class="w-full h-full object-cover">
                                                                @else
                                                                    <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <span class="text-sm font-semibold text-gray-800">{{ $enrollment->course->title }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="p-3">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-32 bg-gray-100 rounded-full h-2">
                                                                 <div class="h-2 rounded-full {{ $enrollment->progress_percent == 100 ? 'bg-emerald-500' : 'bg-[#5A4BFF]' }}" style="width: {{ $enrollment->progress_percent }}%"></div>
                                                            </div>
                                                            <span class="text-xs font-bold text-gray-500 w-8">{{ $enrollment->progress_percent }}%</span>
                                                        </div>
                                                    </td>
                                                    <td class="p-3 text-center">
                                                        @php
                                                            $cert = $student->certificates->where('course_id', $enrollment->course_id)->first();
                                                        @endphp
                                                        @if($cert)
                                                            <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-black uppercase rounded-lg border border-amber-200">
                                                                Awarded
                                                            </span>
                                                        @else
                                                            <span class="px-2 py-0.5 bg-gray-50 text-gray-400 text-[10px] font-bold uppercase rounded-lg border border-gray-100 italic">
                                                                Not Issued
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="p-3 text-xs text-gray-500 font-medium">
                                                        {{ $enrollment->created_at->format('M d, Y') }}
                                                    </td>
                                                    <td class="p-3 text-right">
                                                        <form method="POST" action="{{ route('instructor.students.revoke', $enrollment->id) }}" onsubmit="return confirm('WARNING: Are you sure you want to revoke this student\'s access to {{ addslashes($enrollment->course->title) }}? This cannot be undone.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-xs font-semibold text-red-500 hover:text-white hover:bg-red-500 rounded px-2 py-1 transition">
                                                                Revoke
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-16 text-center">
                            <div class="mx-auto w-16 h-16 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-900 mb-1">No students yet</h3>
                            <p class="text-sm text-gray-500 max-w-sm mx-auto">When students enroll in your courses, they will appear here along with their learning progress.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection
