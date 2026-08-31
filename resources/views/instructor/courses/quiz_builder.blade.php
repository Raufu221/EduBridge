@extends('layouts.instructor')

@section('title', 'Build Quiz: ' . $lesson->title)

@section('content')
    <div class="max-w-4xl mx-auto relative">
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('instructor.course.builder', $course->id) }}" class="text-sm text-gray-500 hover:text-[#5A4BFF] transition flex items-center gap-1 mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Curriculum
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Quiz Builder: {{ $lesson->title }}</h1>
            </div>
            <button onclick="document.getElementById('questionModal').classList.remove('hidden')" class="px-4 py-2 bg-[#5A4BFF] text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Question
            </button>
        </div>

        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse($lesson->quiz->questions ?? [] as $question)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 relative">
                    <div class="absolute top-4 right-4 flex items-center gap-2">
                        <a href="{{ route('instructor.quiz.question.edit', [$course->id, $lesson->id, $question->id]) }}" class="text-gray-400 hover:text-[#5A4BFF] transition" title="Edit Question">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <form action="{{ route('instructor.quiz.question.destroy', [$course->id, $lesson->id, $question->id]) }}" method="POST" onsubmit="return confirm('Delete this question?');" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-gray-400 hover:text-red-500 transition" title="Delete Question"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                        </form>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-700 flex-shrink-0">
                            {{ $loop->iteration }}
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $question->question_text }}</h3>
                            <div class="space-y-2">
                                @foreach($question->options as $option)
                                    <div class="p-3 rounded-lg border {{ $option->is_correct ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }} flex items-center gap-3">
                                        @if($option->is_correct)
                                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        @else
                                            <div class="w-5 h-5 rounded-full border border-gray-300"></div>
                                        @endif
                                        <span class="text-sm font-medium {{ $option->is_correct ? 'text-green-800' : 'text-gray-700' }}">{{ $option->option_text }}</span>
                                    </div>
                                @endforeach
                            </div>
                            @if($question->rationale)
                                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800">
                                    <strong>Rationale:</strong> {{ $question->rationale }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <span class="inline-block px-2 py-1 bg-indigo-50 text-[#5A4BFF] text-xs font-bold rounded">{{ $question->points }} pts</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white border border-gray-200 rounded-xl shadow-sm">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-lg font-medium text-gray-900">No questions yet</h3>
                    <p class="text-sm text-gray-500 mt-1 mb-4">Click "Add Question" to start building your quiz.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Add Question Modal -->
    <div id="questionModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-lg text-gray-900">Add Multiple Choice Question</h3>
                <button type="button" onclick="document.getElementById('questionModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            
            <form action="{{ route('instructor.quiz.question.store', [$course->id, $lesson->id]) }}" method="POST" class="p-6">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Question Text</label>
                    <textarea name="question_text" rows="3" required placeholder="What is the capital of..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition"></textarea>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Points for correct answer</label>
                    <input type="number" name="points" value="1" required min="1" class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition">
                </div>

                <div class="space-y-3 mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Answers (Select the correct one)</label>
                    
                    @for($i = 0; $i < 4; $i++)
                    <div class="flex items-center gap-3">
                        <input type="radio" name="correct_option" value="{{ $i }}" {{ $i == 0 ? 'checked' : '' }} class="w-5 h-5 text-[#5A4BFF] focus:ring-[#5A4BFF]">
                        <input type="text" name="options[]" required placeholder="Option {{ $i+1 }}" class="flex-grow px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition">
                    </div>
                    @endfor
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rationale / Explanation (Optional)</label>
                    <textarea name="rationale" rows="2" placeholder="Explain why the correct answer is correct..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition"></textarea>
                    <p class="text-xs text-gray-500 mt-1">This will be shown to students after they submit their answer.</p>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('questionModal').classList.add('hidden')" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#5A4BFF] text-white rounded-lg font-medium hover:bg-indigo-700 transition">Save Question</button>
                </div>
            </form>
        </div>
    </div>
@endsection
