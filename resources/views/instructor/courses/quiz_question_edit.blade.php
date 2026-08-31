@extends('layouts.instructor')

@section('title', 'Edit Question: ' . $lesson->title)

@section('content')
    <div class="max-w-2xl mx-auto relative mt-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('instructor.quiz.build', [$course->id, $lesson->id]) }}" class="text-sm text-gray-500 hover:text-[#5A4BFF] transition flex items-center gap-1 mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Quiz
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Edit Question</h1>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form action="{{ route('instructor.quiz.question.update', [$course->id, $lesson->id, $question->id]) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Question Text</label>
                    <textarea name="question_text" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition">{{ $question->question_text }}</textarea>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Points for correct answer</label>
                    <input type="number" name="points" value="{{ $question->points }}" required min="1" class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition">
                </div>

                <div class="space-y-3 mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Answers (Select the correct one)</label>
                    
                    @foreach($question->options->take(4) as $i => $option)
                    <div class="flex items-center gap-3">
                        <input type="radio" name="correct_option" value="{{ $i }}" {{ $option->is_correct ? 'checked' : '' }} class="w-5 h-5 text-[#5A4BFF] focus:ring-[#5A4BFF]">
                        <input type="text" name="options[]" value="{{ $option->option_text }}" required placeholder="Option {{ $i+1 }}" class="flex-grow px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition">
                    </div>
                    @endforeach

                    {{-- Just in case there were less than 4 options previously --}}
                    @for($i = $question->options->count(); $i < 4; $i++)
                    <div class="flex items-center gap-3">
                        <input type="radio" name="correct_option" value="{{ $i }}" class="w-5 h-5 text-[#5A4BFF] focus:ring-[#5A4BFF]">
                        <input type="text" name="options[]" required placeholder="Option {{ $i+1 }}" class="flex-grow px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition">
                    </div>
                    @endfor
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rationale / Explanation (Optional)</label>
                    <textarea name="rationale" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition">{{ $question->rationale }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">This will be shown to students after they submit their answer.</p>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('instructor.quiz.build', [$course->id, $lesson->id]) }}" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-[#5A4BFF] text-white rounded-lg font-medium hover:bg-indigo-700 transition">Update Question</button>
                </div>
            </form>
        </div>
    </div>
@endsection
