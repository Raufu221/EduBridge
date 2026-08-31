<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Learning: {{ $course->title }} | {{ config('app.name', 'EduBridge') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    {{-- YouTube IFrame API for auto-completion detection --}}
    @if($lesson && $lesson->type === 'video' && $lesson->video_url)
    <script src="https://www.youtube.com/iframe_api"></script>
    @endif
</head>
<body class="h-full flex overflow-hidden text-gray-900" 
      x-data="{ 
        sidebarOpen: true,
        aiPanelOpen: false,
        showLockedModal: false,
        chatHistory: [],
        userQuestion: '',
        isAsking: false,
        lockedData: { progress: {{ $percent }}, average: {{ $averageScore }}, title: @js($course->title) },
        
        // Review Modal State
        showReviewModal: false,
        reviewRating: @js(optional($existingReview)->rating ?? 0),
        hoverRating: 0,
        reviewComment: @js(optional($existingReview)->comment ?? ""),

        async askAITutor() {
            if (!this.userQuestion.trim() || this.isAsking) return;
            
            const question = this.userQuestion;
            const lessonId = {{ $lesson->id ?? 'null' }};
            if (!lessonId) return;

            this.chatHistory.push({ role: 'user', text: question });
            this.userQuestion = '';
            this.isAsking = true;
            this.scrollToBottom();

            try {
                const response = await fetch('{{ route('learner.ai.ask') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        lesson_id: lessonId,
                        question: question
                    })
                });

                const data = await response.json();
                this.isAsking = false;

                if (data.answer) {
                    this.chatHistory.push({ role: 'ai', text: data.answer });
                } else {
                    this.chatHistory.push({ role: 'ai', text: data.error || 'Error: ' + JSON.stringify(data) });
                }
            } catch (err) {
                this.isAsking = false;
                this.chatHistory.push({ role: 'ai', text: 'Connection error.' });
            }
            this.scrollToBottom();
        },

        scrollToBottom() {
            setTimeout(() => {
                const container = document.getElementById('chatContainer');
                if (container) container.scrollTop = container.scrollHeight;
            }, 50);
        }
      }"
      @keydown.window.escape="aiPanelOpen = false">

    <!-- Sidebar -->
    <aside class="w-80 flex-shrink-0 bg-white border-r border-gray-200 flex flex-col transition-all duration-300 relative z-20"
           :class="{ '-ml-80': !sidebarOpen }">
        <!-- Sidebar Header -->
        <div class="h-16 flex items-center justify-between px-4 border-b border-gray-200 bg-gray-50 flex-shrink-0">
            <a href="{{ route('learner.dashboard') }}" class="font-bold text-gray-700 hover:text-indigo-600 flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Dashboard
            </a>
            <button @click="sidebarOpen = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-4 border-b border-gray-200 flex-shrink-0">
            <h1 class="font-bold text-lg leading-tight mb-2 truncate" title="{{ $course->title }}">{{ $course->title }}</h1>
            <!-- Simple Progress -->
            <div class="flex items-center gap-2 mb-1">
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                </div>
                <span class="text-xs font-medium text-gray-500">{{ $percent }}%</span>
            </div>
            <p class="text-xs text-gray-500">{{ count($completedLessonIds ?? []) }} / {{ $course->modules->sum(function($m) { return $m->lessons->count(); }) }} complete</p>
        </div>

        <!-- Modules / Lessons List -->
        <div class="flex-1 overflow-y-auto">
            @forelse($course->modules as $module)
                <div x-data="{ expanded: true }" class="border-b border-gray-100 last:border-b-0">
                    <button @click="expanded = !expanded" class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 focus:outline-none transition">
                        <div class="flex-1 text-left">
                            <h3 class="font-bold text-sm text-gray-800">{{ $module->title }}</h3>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 transform transition-transform" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="expanded" x-collapse>
                        <ul class="divide-y divide-gray-50">
                            @foreach($module->lessons as $modLesson)
                                @php
                                    $isCurrent = $lesson && $modLesson->id === $lesson->id;
                                    $isCompleted = in_array($modLesson->id, $completedLessonIds ?? []);
                                @endphp
                                <li>
                                    <a href="{{ route('learner.course.viewer', ['course' => $course->id, 'lesson' => $modLesson->id]) }}" 
                                       class="flex items-start p-3 gap-3 hover:bg-gray-50 transition {{ $isCurrent ? 'bg-indigo-50 border-l-4 border-indigo-500' : 'pl-4 border-l-0 border-transparent' }}">
                                        
                                        <!-- Checkmark icon -->
                                        <div class="flex-shrink-0 mt-0.5">
                                            @if($isCompleted)
                                                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            @else
                                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 {{ $isCurrent ? 'border-indigo-400' : '' }}"></div>
                                            @endif
                                        </div>
                                        
                                        <!-- Lesson Info -->
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium {{ $isCurrent ? 'text-indigo-700' : 'text-gray-700' }} line-clamp-2">
                                                {{ $modLesson->title }}
                                            </p>
                                            <div class="flex items-center gap-1 mt-1 text-xs text-gray-400">
                                                @if($modLesson->type === 'video')
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                @elseif($modLesson->type === 'quiz')
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                @elseif($modLesson->type === 'assignment')
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                @else
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                @endif
                                                {{ $modLesson->duration ?? '5m' }}
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-sm text-gray-500">
                    No curriculum available.
                </div>
            @endforelse
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-w-0 bg-white relative">
        <!-- Top Nav -->
        <header class="h-16 flex items-center justify-between px-4 border-b border-gray-200 flex-shrink-0 w-full relative z-10 bg-white">
            <div class="flex items-center gap-4 min-w-0">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 p-2 rounded-lg transition focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h2 class="font-bold text-gray-800 text-lg truncate hidden sm:block">{{ $course->title }}</h2>
                
                <!-- Eligibility Star Icon -->
                <div class="flex items-center ml-2">
                    <button @click="showLockedModal = true" 
                            class="p-1.5 rounded-full transition-all duration-300 transform hover:scale-110"
                            :class="{{ $isEligible ? 'true' : 'false' }} ? 'bg-amber-100 text-amber-500 shadow-sm' : 'bg-gray-100 text-gray-300'"
                            title="{{ $isEligible ? 'You are eligible for certification!' : 'Check certification requirements' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M8 1a3.5 3.5 0 0 0-3.5 3.5V7A1.5 1.5 0 0 0 3 8.5v5A1.5 1.5 0 0 0 4.5 15h7a1.5 1.5 0 0 0 1.5-1.5v-5A1.5 1.5 0 0 0 11.5 7V4.5A3.5 3.5 0 0 0 8 1Zm2 6V4.5a2 2 0 1 0-4 0V7h4Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
            
            @if($lesson && !in_array($lesson->id, $completedLessonIds ?? []))
                @if($lesson->type === 'video' || $lesson->type === 'text' || $lesson->type === 'article' || $lesson->type === 'resource')
                    <button id="markCompleteBtn" onclick="markLessonComplete({{ $lesson->id }})" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition flex items-center gap-2 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Mark Complete
                    </button>
                @endif
            @elseif($lesson && in_array($lesson->id, $completedLessonIds ?? []))
                <button class="bg-emerald-50 text-emerald-600 px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 cursor-default border border-emerald-100 whitespace-nowrap" disabled>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    Completed
                </button>
            @endif

            <!-- Leave Review Button -->
            <button @click="showReviewModal = true" 
                    class="ml-3 p-2 rounded-lg transition {{ $existingReview ? 'text-amber-500 bg-amber-50 hover:bg-amber-100' : 'text-gray-500 hover:text-indigo-600 hover:bg-indigo-50' }}" 
                    title="{{ $existingReview ? 'Edit your review' : 'Rate this course' }}">
                <svg class="w-6 h-6" fill="{{ $existingReview ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
            </button>

            <!-- Certificate Button -->
            @if($certificate)
                <a href="{{ route('learner.certificate.download', $certificate->id) }}" class="ml-3 bg-amber-100 text-amber-700 px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-amber-200 transition shadow-sm border border-amber-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download Certificate
                </a>
            @elseif($isEligible)
                <button @click="$dispatch('open-claim-modal')" class="ml-3 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd" d="M8 1a3.5 3.5 0 0 0-3.5 3.5V7A1.5 1.5 0 0 0 3 8.5v5A1.5 1.5 0 0 0 4.5 15h7a1.5 1.5 0 0 0 1.5-1.5v-5A1.5 1.5 0 0 0 11.5 7V4.5A3.5 3.5 0 0 0 8 1Zm2 6V4.5a2 2 0 1 0-4 0V7h4Z" clip-rule="evenodd" />
                    </svg> 
                    Claim Certificate
                </button>
            @endif
            
            <!-- AI Tutor Toggle - Premium Design -->
            <button @click="aiPanelOpen = !aiPanelOpen" 
                    class="ml-3 relative flex items-center gap-2.5 px-4 py-2 rounded-xl font-bold text-sm transition-all duration-300 overflow-hidden shadow-lg group"
                    :class="aiPanelOpen 
                        ? 'bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-indigo-200' 
                        : 'bg-gradient-to-r from-violet-500 to-indigo-600 text-white hover:shadow-indigo-300 hover:shadow-xl hover:-translate-y-0.5'"
                    title="Open AI Tutor">
                <!-- Glow effect -->
                <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300 rounded-xl"></span>
                <!-- Logo -->
                <img src="{{ asset('images/edubridge_icon.png') }}" alt="EduBridge" class="w-5 h-5 object-contain rounded-sm shrink-0">
                <!-- Text -->
                <span class="hidden md:block tracking-wide">AI Tutor</span>
                <!-- Sparkle icon -->
                <svg class="w-3.5 h-3.5 hidden md:block opacity-80" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <!-- Pulse dot when closed -->
                <span x-show="!aiPanelOpen" class="absolute -top-1 -right-1 flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-400 border-2 border-white"></span>
                </span>
            </button>
        </header>

        <!-- Lesson Content Area -->
        <div class="flex-1 overflow-y-auto w-full relative z-0 bg-gray-50">
            @if($lesson)
                @if(session('success'))
                    <div class="bg-emerald-50 border-b border-emerald-100 p-4 text-emerald-700 flex items-center justify-center font-medium">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border-b border-red-100 p-4 text-red-700 flex items-center justify-center font-medium">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="max-w-5xl mx-auto w-full bg-white pb-24 min-h-full shadow-sm">
                    
                    @if($lesson->type === 'video' || $lesson->type === 'text' || $lesson->type === 'article' || $lesson->type === 'resource')
                        <!-- STANDARD LESSON RENDERER -->
                        @if($lesson->type === 'video')
                            @if($lesson->video_path)
                                <div class="aspect-video w-full bg-black relative shadow-sm overflow-hidden rounded-b-none">
                                    <video id="localVideoPlayer" class="w-full h-full" controls controlsList="nodownload">
                                        <source src="{{ asset('storage/' . $lesson->video_path) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            @elseif($lesson->video_url)
                                @php
                                    $ytId = '';
                                    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $lesson->video_url, $match)) {
                                        $ytId = $match[1];
                                    }
                                @endphp
                                <div class="aspect-video w-full bg-black relative shadow-sm">
                                    @if($ytId)
                                        <iframe 
                                            id="yt-player"
                                            class="absolute inset-0 w-full h-full"
                                            src="https://www.youtube.com/embed/{{ $ytId }}?enablejsapi=1&rel=0&modestbranding=1" 
                                            frameborder="0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen>
                                        </iframe>
                                    @else
                                        <div class="absolute inset-0 flex items-center justify-center text-gray-400 p-8 text-center bg-gray-900">
                                            <div class="space-y-4">
                                                <svg class="w-12 h-12 mx-auto opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                <p class="text-sm font-bold">Invalid Video URL</p>
                                                <p class="text-xs opacity-60">Format not recognized: {{ $lesson->video_url }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endif

                        <div class="p-6 md:p-12">
                            <div class="mb-10 text-center sm:text-left">
                                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-3 tracking-tight">{{ $lesson->title }}</h1>
                                <div class="inline-flex items-center justify-center sm:justify-start text-sm text-indigo-600 font-bold bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                                    <span>{{ $lesson->module->title }}</span>
                                </div>
                            </div>
                            <div class="prose prose-lg prose-indigo max-w-none text-gray-700 leading-relaxed font-medium">
                                {!! nl2br(e($lesson->content)) !!}
                            </div>

                            @if($lesson->resource_file)
                                <div class="mt-12 p-6 bg-[#F8F9FA] border border-gray-200 rounded-3xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="p-3.5 bg-indigo-50 text-indigo-600 rounded-2xl">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Supplementary Material</p>
                                            <h4 class="text-sm font-black text-gray-800 tracking-tight">{{ $lesson->resource_name ?? 'Download Resource' }}</h4>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $lesson->resource_file) }}" download class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest py-3.5 px-6 rounded-2xl transition shadow-lg shadow-indigo-100 text-center">
                                        Download File
                                    </a>
                                </div>
                            @endif
                        </div>

                    @elseif($lesson->type === 'quiz')
                        <!-- QUIZ RENDERER -->
                        @if($lesson->quiz)
                            @php
                                $attempt = \App\Models\QuizAttempt::where('quiz_id', $lesson->quiz->id)->where('user_id', Auth::id())->latest()->first();
                            @endphp
                            <div class="p-8 md:p-16">
                                <div class="mb-10 text-center border-b border-gray-100 pb-8">
                                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">{{ $lesson->title }}</h1>
                                    <div class="flex flex-wrap justify-center gap-4 text-sm font-semibold text-gray-500">
                                        <span class="bg-gray-100 px-3 py-1 rounded-lg">Time Limit: {{ $lesson->quiz->time_limit_minutes }} mins</span>
                                        <span class="bg-gray-100 px-3 py-1 rounded-lg">Passing Score: {{ $lesson->quiz->passing_percent }}%</span>
                                    </div>
                                </div>
                                
                                @if($attempt && $attempt->passed)
                                    <!-- Passed State -->
                                    <div class="bg-emerald-50 border border-emerald-200 rounded-3xl p-10 text-center max-w-xl mx-auto shadow-sm">
                                        <div class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                            <svg class="w-12 h-12 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                        </div>
                                        <h2 class="text-3xl font-extrabold text-emerald-900 mb-3">You Passed!</h2>
                                        <div class="text-emerald-700 text-lg mb-6">
                                            Your Score: <span class="font-bold text-2xl text-emerald-800 mx-1">{{ $attempt->score }} / {{ $attempt->total_points }}</span> 
                                            <span class="bg-emerald-200 text-emerald-900 px-2 py-0.5 rounded text-sm ml-2">{{ round(($attempt->score / $attempt->total_points) * 100) }}%</span>
                                        </div>
                                        <p class="text-sm font-medium text-emerald-600 bg-emerald-100/50 inline-block px-4 py-2 rounded-full border border-emerald-200">This quiz is complete.</p>
                                    </div>
                                @else
                                    <form action="{{ route('learner.quiz.submit', $lesson->quiz->id) }}" method="POST">
                                        @csrf
                                        <div class="space-y-10 max-w-3xl mx-auto">
                                            @foreach($lesson->quiz->questions as $i => $question)
                                                <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-sm">
                                                    <h3 class="font-bold text-xl text-gray-900 mb-6 flex items-start">
                                                        <span class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center shrink-0 mr-4 text-sm mt-0.5">{{ $i+1 }}</span>
                                                        <span>{{ $question->question_text }} <span class="text-sm font-medium text-gray-400 block mt-1">{{ $question->points }} Points</span></span>
                                                    </h3>
                                                    <div class="space-y-3 pl-12">
                                                        @foreach($question->options as $option)
                                                            <label class="flex items-center p-4 rounded-xl hover:bg-gray-50 border border-gray-100 hover:border-indigo-200 transition cursor-pointer group">
                                                                <input type="radio" name="question_{{ $question->id }}" value="{{ $option->id }}" required class="w-5 h-5 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                                                <span class="ml-4 text-gray-700 font-medium group-hover:text-gray-900">{{ $option->option_text }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        @if($lesson->quiz->questions->count() > 0)
                                            <div class="mt-12 text-center max-w-3xl mx-auto">
                                                <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-4 px-12 rounded-2xl shadow-xl shadow-indigo-200 transition hover:-translate-y-0.5 active:translate-y-0 text-lg">
                                                    Submit Answers
                                                </button>
                                            </div>
                                        @else
                                            <div class="text-center text-gray-500 border-2 border-dashed rounded-3xl p-12 max-w-3xl mx-auto">
                                                No questions have been added to this quiz yet.
                                            </div>
                                        @endif
                                    </form>
                                @endif
                            </div>
                        @else
                            <div class="p-12 text-center text-gray-500">Instructor has not setup the Quiz rules yet.</div>
                        @endif

                    @elseif($lesson->type === 'assignment')
                        <!-- ASSIGNMENT RENDERER -->
                        @if($lesson->assignment)
                            @php
                                $submission = \App\Models\AssignmentSubmission::where('assignment_id', $lesson->assignment->id)->where('user_id', Auth::id())->first();
                            @endphp
                            <div class="p-8 md:p-16 max-w-4xl mx-auto">
                                <div class="mb-10 text-center border-b border-gray-100 pb-8">
                                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">{{ $lesson->title }}</h1>
                                    <div class="flex flex-wrap justify-center gap-4 text-sm font-semibold text-gray-500">
                                        <span class="bg-gray-100 px-3 py-1 rounded-lg">Max Marks: {{ $lesson->assignment->total_marks }}</span>
                                        <span class="bg-gray-100 px-3 py-1 rounded-lg">Passing Criteria: {{ $lesson->assignment->passing_marks }} Marks</span>
                                    </div>
                                </div>
                                
                                <div class="bg-white border border-gray-200 shadow-sm rounded-3xl p-8 mb-10">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Instructions
                                    </h3>
                                    <div class="prose prose-lg prose-indigo max-w-none text-gray-700">
                                        {!! nl2br(e($lesson->content)) !!}
                                        @if(empty($lesson->content))
                                            <span class="italic text-gray-400">Please review the supplementary materials or upload your project file.</span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($submission)
                                    <!-- Submitted State -->
                                    <div class="bg-gray-50 border border-gray-200 rounded-3xl p-10 text-center">
                                        <div class="w-20 h-20 bg-white shadow-sm border border-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                            <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Assignment Submitted</h2>
                                        <p class="text-gray-600 mb-8 max-w-sm mx-auto">Your file has been secured and sent to the instructor for evaluation.</p>
                                        
                                        @if($submission->status === 'graded')
                                            <div class="bg-indigo-50 border border-indigo-100 p-6 rounded-2xl max-w-sm mx-auto">
                                                <p class="text-sm font-bold text-indigo-900 mb-1 uppercase tracking-wider">Final Grade</p>
                                                <div class="text-indigo-700 font-extrabold text-4xl mb-4">
                                                    {{ $submission->marks_awarded }} <span class="text-xl text-indigo-400 font-medium">/ {{ $lesson->assignment->total_marks }}</span>
                                                </div>
                                                @if($submission->feedback)
                                                    <div class="bg-white rounded-xl p-4 text-sm text-gray-700 text-left border border-indigo-50">
                                                        <span class="block font-bold text-gray-900 mb-1 text-xs uppercase">Instructor Feedback:</span>
                                                        {{ $submission->feedback }}
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="bg-amber-50 text-amber-800 p-4 rounded-xl inline-flex items-center gap-2 font-bold shadow-sm border border-amber-200">
                                                <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Status: Pending Instructor Review
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <form action="{{ route('learner.assignment.submit', $lesson->assignment->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl p-10 border-2 border-dashed border-gray-300 hover:border-indigo-400 transition-colors bg-gray-50/50">
                                        @csrf
                                        <div class="text-center mb-8">
                                            <div class="w-16 h-16 bg-white shadow-sm border border-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <svg class="w-8 h-8 text-indigo-500" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                            </div>
                                            <h3 class="text-xl font-bold text-gray-900 mb-1">Upload Submission</h3>
                                            <p class="text-sm font-medium text-gray-500">Attach your completed project or document here.</p>
                                        </div>
                                        
                                        <div class="max-w-md mx-auto">
                                            <div class="flex justify-center mb-8 border border-gray-200 bg-white rounded-xl p-2 shadow-sm">
                                                <input type="file" name="file" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-extrabold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                            </div>
                                            
                                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-4 px-8 rounded-xl shadow-xl shadow-indigo-200 transition hover:-translate-y-0.5 active:translate-y-0 text-center flex justify-center items-center gap-2 text-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                Submit Assignment
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        @else
                            <div class="p-12 text-center text-gray-500">Instructor has not configured this assignment yet.</div>
                        @endif
                    @endif

                </div>
            @else
                <div class="flex flex-col items-center justify-center h-full text-center p-6 bg-white min-h-[500px]">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6 border border-gray-100 shadow-sm">
                        <svg class="w-12 h-12 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-3 tracking-tight">Welcome to <span class="text-indigo-600">{{ $course->title }}</span></h2>
                    <p class="text-lg text-gray-500 max-w-md mx-auto font-medium">Select a lesson from the sidebar to begin your learning journey.</p>
                </div>
            @endif
        </div>

        <!-- AI TUTOR SLIDE-OVER PANEL -->
        <aside x-show="aiPanelOpen" 
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-200"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="translate-x-full"
               class="fixed right-0 top-0 h-full w-full max-w-sm bg-white shadow-2xl border-l border-gray-200 z-[100] flex flex-col"
               x-cloak>
            
            <!-- Panel Header -->
            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-violet-600 to-indigo-600">
                <div class="flex items-center gap-3">
                    <!-- EduBridge Logo -->
                    <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center shadow-md shrink-0">
                        <img src="{{ asset('images/edubridge_icon.png') }}" alt="EduBridge" class="w-7 h-7 object-contain">
                    </div>
                    <div>
                        <h3 class="font-black text-white leading-none text-base">AI Tutor</h3>
                        <p class="text-[10px] font-bold text-indigo-200 uppercase tracking-widest mt-0.5">Lesson Assistant</p>
                    </div>
                </div>
                <button @click="aiPanelOpen = false" class="text-indigo-200 hover:text-white transition p-1.5 hover:bg-white/10 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Chat Messages Area -->
            <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50/50" id="chatContainer">
                <!-- Welcome Message -->
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-violet-500 to-indigo-600 rounded-full flex items-center justify-center shadow-md shrink-0 p-1.5">
                        <img src="{{ asset('images/edubridge_icon.png') }}" alt="EduBridge AI" class="w-full h-full object-contain">
                    </div>
                    <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-gray-100 max-w-[85%]">
                        <p class="text-xs font-bold text-indigo-500 mb-1">EduBridge AI Tutor</p>
                        <p class="text-sm text-gray-700 leading-relaxed">
                            Hello! I'm your EduBridge AI Tutor. Ask me anything about this lesson, and I'll answer based on the instructor's notes.
                        </p>
                    </div>
                </div>

                <!-- Chat History Loop -->
                <template x-for="(msg, index) in chatHistory" :key="index">
                    <div class="flex flex-col" :class="msg.role === 'user' ? 'items-end' : 'items-start'">
                        <div class="flex items-start gap-2 max-w-[85%]" :class="msg.role === 'user' ? 'flex-row-reverse' : ''">
                            <!-- Avatar -->
                            <template x-if="msg.role === 'ai'">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center shadow-md shrink-0 p-1.5">
                                    <img src="{{ asset('images/edubridge_icon.png') }}" alt="AI" class="w-full h-full object-contain">
                                </div>
                            </template>
                            <template x-if="msg.role === 'user'">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 border border-indigo-200 flex items-center justify-center shadow-sm shrink-0">
                                    <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                                </div>
                            </template>
                            <!-- Bubble -->
                            <div class="p-3.5 rounded-2xl shadow-sm border"
                                 :class="msg.role === 'user' ? 'bg-indigo-600 text-white border-indigo-500 rounded-tr-none' : 'bg-white text-gray-700 border-gray-100 rounded-tl-none'">
                                <p class="text-sm leading-relaxed whitespace-pre-wrap" x-text="msg.text"></p>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Loading / Typing Indicator -->
                <div x-show="isAsking" class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center shadow-md shrink-0 p-1.5">
                        <img src="{{ asset('images/edubridge_icon.png') }}" alt="AI" class="w-full h-full object-contain">
                    </div>
                    <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-gray-100 flex items-center gap-1.5">
                        <span class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce"></span>
                        <span class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce [animation-delay:0.15s]"></span>
                        <span class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce [animation-delay:0.3s]"></span>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-6 bg-white border-t border-gray-100">
                @if($lesson)
                <div class="relative">
                    <textarea 
                        x-model="userQuestion"
                        @keydown.enter.prevent="askAITutor()"
                        rows="2"
                        placeholder="Ask a question about this lesson..."
                        class="w-full pl-4 pr-12 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-sm resize-none"
                    ></textarea>
                    <button 
                        @click="askAITutor()"
                        :disabled="isAsking || !userQuestion.trim()"
                        class="absolute right-2 bottom-2 p-2 rounded-xl transition disabled:opacity-50"
                        :class="userQuestion.trim() ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-400'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
                <p class="text-[9px] text-gray-400 mt-2 text-center uppercase font-bold tracking-widest">Powered by EduBridge AI · Llama 3.3</p>
                @else
                <p class="text-center text-sm text-gray-500 italic">Select a lesson to start chatting.</p>
                @endif
            </div>
        </aside>
    </main>

    <!-- Review Modal -->
    <div 
        x-show="showReviewModal" 
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true"
    >
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showReviewModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" aria-hidden="true" @click="showReviewModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showReviewModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-8">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-black text-gray-900 leading-none">
                            {{ $existingReview ? 'Update Your Review' : 'Rate this Course' }}
                        </h3>
                        <button @click="showReviewModal = false" class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <form action="{{ route('learner.course.review', $course->id) }}" method="POST">
                        @csrf
                        <div class="mb-8">
                            <label class="block text-sm font-bold text-gray-700 mb-4 uppercase tracking-widest text-center">Your Rating</label>
                            <div class="flex items-center justify-center gap-2">
                                <template x-for="i in 5">
                                    <button 
                                        type="button" 
                                        @click="reviewRating = i" 
                                        @mouseenter="hoverRating = i" 
                                        @mouseleave="hoverRating = 0"
                                        class="focus:outline-none transition-transform hover:scale-110"
                                    >
                                        <svg 
                                            class="w-12 h-12 transition-colors duration-150" 
                                            :class="(hoverRating || reviewRating) >= i ? 'text-amber-400 fill-current' : 'text-gray-200'"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        >
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </button>
                                </template>
                            </div>
                            <input type="hidden" name="rating" x-model="reviewRating">
                        </div>

                        <div class="mb-8">
                            <label for="comment" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">
                                {{ $existingReview ? 'Update your feedback' : 'Share more details (Optional)' }}
                            </label>
                            <textarea 
                                name="comment" 
                                id="comment" 
                                rows="4" 
                                x-model="reviewComment"
                                class="w-full rounded-2xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 font-medium placeholder-gray-400 shadow-sm"
                                placeholder="What did you like or dislike about the course?"
                            ></textarea>
                        </div>

                        <div class="flex flex-col gap-3">
                            <button 
                                type="submit" 
                                :disabled="!reviewRating"
                                :class="!reviewRating ? 'bg-gray-200 cursor-not-allowed text-gray-400' : 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-200 hover:-translate-y-0.5'"
                                class="w-full py-4 rounded-2xl font-extrabold text-lg transition-all active:translate-y-0"
                            >
                                {{ $existingReview ? 'Update Feedback' : 'Submit Review' }}
                            </button>
                            <button 
                                type="button" 
                                @click="showReviewModal = false" 
                                class="w-full py-3 text-gray-500 font-bold hover:text-gray-700 transition"
                            >
                                Maybe Later
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Claim Certificate Modal -->
    <div 
        x-data="{ open: false, fullName: '{{ Auth::user()->name }}' }" 
        x-show="open" 
        @open-claim-modal.window="open = true"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
    >
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" aria-hidden="true" @click="open = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-8">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900">Claim Your Certificate</h3>
                    <p class="text-gray-500 mt-2 font-medium">Please confirm your legal name as it should appear on the official document.</p>
                </div>

                <form action="{{ route('learner.certificate.claim', $course->id) }}" method="POST">
                    @csrf
                    <div class="mb-8">
                        <label for="full_name" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest text-center">Confirm Legal Name</label>
                        <input 
                            type="text" 
                            name="full_name" 
                            id="full_name" 
                            x-model="fullName"
                            required 
                            class="w-full text-center text-xl font-bold rounded-2xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 py-4"
                        >
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-extrabold text-lg shadow-lg shadow-indigo-100 transition hover:-translate-y-0.5">
                            Issue My Certificate
                        </button>
                        <button type="button" @click="open = false" class="w-full py-3 text-gray-500 font-bold hover:text-gray-700">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Locked Requirements Modal (Simulator Style) -->
    <div x-show="showLockedModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[120] overflow-y-auto" x-cloak>
        
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 transition-opacity bg-black/80 backdrop-blur-md" @click="showLockedModal = false"></div>

            <div class="relative inline-block w-full max-w-md overflow-hidden transition-all transform bg-slate-900 shadow-2xl rounded-[2.5rem] border border-slate-800"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                
                <div class="p-8 text-center pt-12">
                    <!-- Huge Lock Icon -->
                    <div class="w-32 h-32 bg-slate-800/50 rounded-full flex items-center justify-center mx-auto mb-8 border border-red-500/20 shadow-2xl shadow-red-500/10 relative">
                        <svg class="w-12 h-12 text-red-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>

                    <h3 class="text-2xl font-black text-white mb-2" x-text="lockedData.title"></h3>
                    <p class="text-xs font-bold text-red-400 uppercase tracking-[0.3em] mb-10">Certification Locked</p>

                    <div class="space-y-4 text-left bg-black/30 p-6 rounded-3xl border border-slate-800">
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4">Requirements Checklist:</h4>
                        
                        <!-- Progress Req -->
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <template x-if="lockedData.progress >= 100">
                                    <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </template>
                                <template x-if="lockedData.progress < 100">
                                    <svg class="w-5 h-5 text-slate-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                </template>
                                <span class="text-sm font-bold" :class="lockedData.progress >= 100 ? 'text-white' : 'text-slate-400'">Complete 100% of Course</span>
                            </div>
                            <span class="text-xs font-black" :class="lockedData.progress >= 100 ? 'text-emerald-400' : 'text-slate-500'" x-text="Math.round(lockedData.progress) + '%'"></span>
                        </div>

                        <!-- Score Req -->
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <template x-if="lockedData.average >= 80">
                                    <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </template>
                                <template x-if="lockedData.average < 80">
                                    <svg class="w-5 h-5 text-slate-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                </template>
                                <span class="text-sm font-bold" :class="lockedData.average >= 80 ? 'text-white' : 'text-slate-400'">Average Score ≥ 80%</span>
                            </div>
                            <span class="text-xs font-black" :class="lockedData.average >= 80 ? 'text-emerald-400' : 'text-slate-500'" x-text="Math.round(lockedData.average) + '%'"></span>
                        </div>
                    </div>

                    <div class="mt-10">
                        <button @click="showLockedModal = false" class="w-full py-4 bg-slate-800 text-white rounded-2xl font-black text-sm hover:bg-slate-700 transition">
                            Got it
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AJAX complete function -->
    <!-- Auto-completion Script -->
    <script>
        (function() {
            @if($lesson && $lesson->type === 'video' && !in_array($lesson->id, $completedLessonIds ?? []))
            var _lessonId = {{ $lesson->id }};
            var _autoTriggered = false;

            function autoComplete() {
                if (_autoTriggered) return;
                _autoTriggered = true;
                // Small delay so user sees the video ending naturally
                setTimeout(function() {
                    markLessonComplete(_lessonId);
                }, 1500);
            }

            @if($lesson->video_path)
            // ── LOCAL VIDEO: listen for the 'ended' event ──
            document.addEventListener('DOMContentLoaded', function () {
                var vid = document.getElementById('localVideoPlayer');
                if (vid) {
                    vid.addEventListener('ended', autoComplete);
                }
            });
            @endif

            @if($lesson->video_url ?? false)
            // ── YOUTUBE VIDEO: use IFrame API ──
            window.onYouTubeIframeAPIReady = function () {
                new YT.Player('yt-player', {
                    events: {
                        onStateChange: function (e) {
                            // YT.PlayerState.ENDED === 0
                            if (e.data === 0) {
                                autoComplete();
                            }
                        }
                    }
                });
            };
            @endif
            @endif
        })();
    </script>

    <!-- Mark Complete AJAX -->
    <script>
        function markLessonComplete(lessonId) {
            fetch(`/learner/lesson/${lessonId}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'completed' || data.status === 'already_completed') {
                    window.location.reload();
                }
            })
            .catch(err => console.error('Completion error:', err));
        }
    </script>
</body>
</html>
