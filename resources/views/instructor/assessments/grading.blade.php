@extends('layouts.instructor')

@section('title', 'Grading Dashboard')

@section('content')
<div x-data="{ openGradeModal: false, selectedSubmissionId: null, maxMarks: 100, formAction: '' }" class="relative">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Grading</h1>
            <p class="text-sm text-gray-500 mt-1">Review and grade student submissions</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex flex-col justify-center">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-10 h-10 rounded-lg bg-yellow-50 text-yellow-500 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-sm font-semibold text-gray-400 uppercase tracking-widest">Pending Review</p>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">{{ collect($submissions)->where('status', 'pending')->count() }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex flex-col justify-center">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-10 h-10 rounded-lg bg-green-50 text-green-500 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-sm font-semibold text-gray-400 uppercase tracking-widest">Graded</p>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">{{ collect($submissions)->where('status', 'graded')->count() }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex flex-col justify-center">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <p class="text-sm font-semibold text-gray-400 uppercase tracking-widest">Total Submissions</p>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">{{ collect($submissions)->count() }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex flex-col justify-center">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-10 h-10 rounded-lg bg-fuchsia-50 text-fuchsia-500 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <p class="text-sm font-semibold text-gray-400 uppercase tracking-widest">Avg Score</p>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">0%</h3>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex gap-2 mb-6">
        <button class="px-5 py-2.5 bg-[#5A4BFF] text-white rounded-full text-sm font-bold transition">Pending ({{ collect($submissions)->where('status', 'pending')->count() }})</button>
        <button class="px-5 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-full text-sm font-bold hover:bg-gray-50 transition">Graded ({{ collect($submissions)->where('status', 'graded')->count() }})</button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 font-bold uppercase tracking-wider">
                    <th class="p-4 rounded-tl-lg">Student</th>
                    <th class="p-4">Assignment</th>
                    <th class="p-4">Course</th>
                    <th class="p-4">Submitted</th>
                    <th class="p-4">File</th>
                    <th class="p-4 text-right rounded-tr-lg">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($submissions as $submission)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 font-medium text-gray-900 flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($submission->user->name ?? 'Student') }}&background=random" class="w-8 h-8 rounded-full">
                            {{ $submission->user->name ?? 'Unknown Student' }}
                        </td>
                        <td class="p-4 text-sm text-gray-900 font-medium">{{ $submission->assignment->lesson->title ?? 'N/A' }}</td>
                        <td class="p-4 text-sm text-gray-500">{{ $submission->assignment->lesson->module->course->title ?? 'N/A' }}</td>
                        <td class="p-4 text-sm text-gray-500">{{ $submission->created_at->diffForHumans() }}</td>
                        <td class="p-4">
                            <!-- FIXED FILE LINK -->
                            <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="text-sm font-medium text-gray-700 hover:text-[#5A4BFF] flex items-center gap-1 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                {{ basename($submission->file_path) }}
                            </a>
                        </td>
                        <td class="p-4 text-right">
                            @if($submission->status === 'graded')
                                <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1.5 rounded-full inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Graded
                                </span>
                            @else
                                <!-- DYNAMIC MODAL TRIGGER -->
                                <button type="button" @click="openGradeModal = true; selectedSubmissionId = {{ $submission->id }}; maxMarks = {{ $submission->assignment->total_marks ?? 100 }}; formAction = '{{ url('/instructor/assessments/grading') }}/' + selectedSubmissionId" class="px-4 py-2 bg-[#5A4BFF] text-white text-xs font-bold rounded shadow-sm hover:bg-indigo-700 transition inline-flex items-center justify-end gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Grade
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-gray-500">
                            No submissions pending review at this time.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- GRADE ASSESSMENT MODAL -->
    <div x-show="openGradeModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="openGradeModal = false"></div>
        
        <!-- Modal Content -->
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md relative z-10 overflow-hidden" @click.stop x-transition>
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/80">
                <h3 class="text-lg font-bold text-gray-900">Provide Grade</h3>
                <button type="button" @click="openGradeModal = false" class="text-gray-400 hover:text-gray-600 transition p-1 hover:bg-gray-200 rounded-full">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form :action="formAction" method="POST" class="p-6">
                @csrf
                
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Marks Awarded</label>
                    <div class="flex items-center gap-3">
                        <input type="number" name="marks_awarded" min="0" :max="maxMarks" class="w-full rounded-lg border-gray-300 focus:border-[#5A4BFF] focus:ring focus:ring-[#5A4BFF]/20 text-sm py-2.5 shadow-sm" placeholder="Score..." required>
                        <span class="text-sm font-bold text-gray-500 whitespace-nowrap bg-gray-100 px-3 py-2 rounded-lg border border-gray-200">/ <span x-text="maxMarks"></span></span>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Feedback Notes (Optional)</label>
                    <textarea name="feedback" rows="3" class="w-full rounded-lg border-gray-300 focus:border-[#5A4BFF] focus:ring focus:ring-[#5A4BFF]/20 text-sm shadow-sm" placeholder="Provide constructive feedback for the student..."></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="openGradeModal = false" class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-white hover:bg-gray-50 rounded-lg transition border border-gray-200 shadow-sm">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-[#5A4BFF] hover:bg-indigo-700 rounded-lg transition shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Submit Grade
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
