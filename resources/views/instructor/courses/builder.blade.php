@extends('layouts.instructor')

@section('title', 'Course Builder')

@section('content')
    <div class="max-w-7xl mx-auto relative" x-data="{ 
        activeTab: 'curriculum', 
        price: {{ $course->price ?? 0 }}, 
        isFree: {{ (!isset($course->price) || $course->price <= 0) ? 'true' : 'false' }}, 
        category_id: '{{ old('category_id', $course->category_id ?? '') }}',
        categories: {{ $categories->toJson() }},
        get activeCategory() {
            return this.categories.find(c => c.id == this.category_id);
        },
        get minLimit() {
            return this.activeCategory ? this.activeCategory.min_price : 500;
        },
        get maxLimit() {
            return this.activeCategory ? this.activeCategory.max_price : 15000;
        },
        get revenue() { return (this.price * 0.70).toFixed(2); }, 
        get fee() { return (this.price * 0.30).toFixed(2); } 
    }">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Course Builder</h1>
                <p class="text-sm text-gray-500 mt-1">Currently building: <span class="font-semibold text-[#5A4BFF]">{{ $course->title }}</span></p>
            </div>
            <div class="flex items-center gap-3 mt-4 md:mt-0">
                <a href="{{ route('instructor.course.preview', $course->id) }}" target="_blank" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Preview
                </a>
                @if(!$course->is_submitted && !$course->is_published && !$course->admin_feedback)
                <form action="{{ route('instructor.course.submit', $course->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" onclick="return confirm('Are you sure you want to submit this course for review?');" class="px-4 py-2 bg-[#5A4BFF] text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition flex items-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Submit for Review
                    </button>
                </form>
                @elseif($course->is_submitted && !$course->is_published)
                    <span class="px-4 py-2 bg-amber-100 text-amber-700 rounded-lg text-sm font-semibold flex items-center gap-2 shadow-sm border border-amber-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        In Review
                    </span>
                @elseif($course->admin_feedback && !$course->is_submitted && !$course->is_published)
                    <span class="px-4 py-2 bg-rose-100 text-rose-700 rounded-lg text-sm font-semibold flex items-center gap-2 shadow-sm border border-rose-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Rejected
                    </span>
                @else
                    <span class="px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm font-semibold flex items-center gap-2 shadow-sm border border-green-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Published
                    </span>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-2 mb-8 bg-gray-100 p-1 rounded-lg w-fit">
            <button @click="activeTab = 'curriculum'" :class="activeTab === 'curriculum' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'" class="px-4 py-1.5 text-sm font-semibold rounded-md transition border border-transparent">Curriculum</button>
            <button @click="activeTab = 'details'"    :class="activeTab === 'details' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'" class="px-4 py-1.5 text-sm font-semibold rounded-md transition border border-transparent">Course Details</button>
            <button @click="activeTab = 'pricing'"    :class="activeTab === 'pricing' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'" class="px-4 py-1.5 text-sm font-semibold rounded-md transition border border-transparent">Pricing</button>
        </div>

        <!-- REJECTION ALERT -->
        @if($course->admin_feedback && !$course->is_submitted && !$course->is_published)
            <div class="mb-8 p-6 bg-rose-50 border-l-4 border-rose-500 rounded-r-2xl shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="mt-1 p-2 bg-rose-100 rounded-lg text-rose-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-rose-900 uppercase tracking-widest">Course Rejected</h3>
                        <p class="text-xs text-rose-700 mt-2 font-medium leading-relaxed">
                            <span class="font-black">Admin Feedback:</span> {{ $course->admin_feedback }}
                        </p>
                        <p class="text-[10px] text-rose-500 mt-3 italic font-medium">Please fix the issues above and click "Save & Resubmit" to send it back for review.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- GLOBAL VALIDATION ERRORS -->
        @if($errors->any())
            <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-sm font-bold text-red-800">Please correct the following errors:</h3>
                </div>
                <ul class="text-xs text-red-700 list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-8 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-sm font-bold text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <!-- CURRICULUM TAB -->
        <div x-show="activeTab === 'curriculum'" x-transition class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-gray-900">Course Curriculum</h2>
                        <button onclick="openModuleModal()" class="px-4 py-2 bg-[#5A4BFF] text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add Module
                        </button>
                    </div>

                    @forelse($course->modules as $module)
                        <div class="border border-gray-200 rounded-lg mb-4 overflow-hidden">
                            <div class="bg-gray-50 p-4 flex items-center justify-between border-b border-gray-200">
                                <div class="flex items-center gap-3 cursor-pointer">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6a2 2 0 11-4 0 2 2 0 014 0zM8 12a2 2 0 11-4 0 2 2 0 014 0zM8 18a2 2 0 11-4 0 2 2 0 014 0zM14 6a2 2 0 11-4 0 2 2 0 014 0zM14 12a2 2 0 11-4 0 2 2 0 014 0zM14 18a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    <div>
                                        <h3 class="font-bold text-sm text-gray-900">Module {{ $loop->iteration }}: {{ $module->title }}</h3>
                                        <p class="text-xs text-gray-500">{{ $module->lessons->count() }} lessons</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="openEditModuleModal({{ $module->id }}, '{{ addslashes($module->title) }}')" class="p-1.5 text-gray-500 hover:text-indigo-600 bg-white border border-gray-200 rounded shadow-sm transition" title="Edit Module"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                    <form action="{{ route('instructor.module.destroy', [$course->id, $module->id]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this module? This will also delete all its lessons.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-500 hover:text-red-600 bg-white border border-gray-200 rounded shadow-sm transition" title="Delete Module"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="bg-white p-2">
                                @foreach($module->lessons as $lesson)
                                    <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg group transition border border-transparent hover:border-gray-100">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-4 h-4 text-gray-300 cursor-grab" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6a2 2 0 11-4 0 2 2 0 014 0zM8 12a2 2 0 11-4 0 2 2 0 014 0zM8 18a2 2 0 11-4 0 2 2 0 014 0zM14 6a2 2 0 11-4 0 2 2 0 014 0zM14 12a2 2 0 11-4 0 2 2 0 014 0zM14 18a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            
                                            @if($lesson->type == 'video')
                                                <div class="w-6 h-6 rounded-full bg-indigo-50 flex items-center justify-center text-[#5A4BFF]">
                                                    <svg class="w-3 h-3 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                </div>
                                            @else
                                                <div class="w-6 h-6 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                </div>
                                            @endif

                                            <div>
                                                <p class="text-sm font-semibold text-gray-800 group-hover:text-[#5A4BFF] transition">{{ $lesson->title }}</p>
                                                <div class="flex items-center gap-3 mt-0.5">
                                                    <p class="text-xs text-gray-500 capitalize">{{ $lesson->type }} {{ $lesson->duration ? '• '.$lesson->duration : '' }}</p>
                                                    @if($lesson->resource_file)
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                            {{ \Illuminate\Support\Str::limit($lesson->resource_name ?? 'Resource', 25) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="opacity-0 group-hover:opacity-100 flex items-center gap-2 transition">
                                            @if($lesson->type === 'quiz')
                                                <a href="{{ route('instructor.quiz.build', [$course->id, $lesson->id]) }}" class="p-1.5 text-gray-500 hover:text-green-600 bg-white border border-gray-200 rounded shadow-sm transition" title="Manage Questions">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                </a>
                                            @endif
                                            <button type="button" onclick="openEditLessonModal({{ $module->id }}, {{ $lesson->id }}, '{{ addslashes($lesson->title) }}', '{{ $lesson->type }}', '{{ $lesson->duration }}', '{{ addslashes($lesson->video_url) }}', '{{ addslashes(preg_replace('/\n|\r/', '\\n', $lesson->content)) }}', '{{ $lesson->video_path }}', '{{ addslashes(preg_replace('/\n|\r/', '\\n', $lesson->transcript)) }}')" class="p-1.5 text-gray-500 hover:text-indigo-600 bg-white border border-gray-200 rounded shadow-sm transition" title="Edit Lesson"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                            <form action="{{ route('instructor.lesson.destroy', [$course->id, $module->id, $lesson->id]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this lesson?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1.5 text-gray-500 hover:text-red-600 bg-white border border-gray-200 rounded shadow-sm transition" title="Delete Lesson"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                                
                                <button onclick="openLessonModal({{ $module->id }})" class="w-full mt-2 py-2 border-2 border-dashed border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-900 transition flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Add Lesson
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 border-2 border-dashed border-gray-200 rounded-lg">
                            <p class="text-gray-500 text-sm">No modules yet. Click "Add Module" to start building your curriculum.</p>
                        </div>
                    @endforelse

                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4">Add Content Tools</h3>
                    <div class="space-y-3">
                        <button onclick="alert('To add a video lecture, click \'Add Lesson\' inside a Module and select Video Lecture.')" class="w-full flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-[#5A4BFF] hover:bg-indigo-50 text-gray-700 hover:text-[#5A4BFF] transition text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2-2V8a2 2 0 002 2z"></path></svg>
                            Video Lecture
                        </button>
                        <button onclick="alert('To add an article, click \'Add Lesson\' inside a Module and select Article/Reading.')" class="w-full flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-blue-500 hover:bg-blue-50 text-gray-700 hover:text-blue-600 transition text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Article/Reading
                        </button>
                        <button onclick="alert('To add a quiz, click \'Add Lesson\' inside a Module, select Quiz, save it, and then click the green Manage Questions icon to add your questions!')" class="w-full flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-fuchsia-500 hover:bg-fuchsia-50 text-gray-700 hover:text-fuchsia-600 transition text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Quiz
                        </button>
                        <button onclick="alert('To add an assignment, click \'Add Lesson\' inside a Module and select Assignment.')" class="w-full flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-emerald-500 hover:bg-emerald-50 text-gray-700 hover:text-emerald-600 transition text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Assignment
                        </button>
                        <button onclick="alert('To upload a resource, click \'Add Lesson\' inside a Module and use the Attached Resource field.')" class="w-full flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-orange-500 hover:bg-orange-50 text-gray-700 hover:text-orange-600 transition text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Upload Resource
                        </button>
                    </div>
                </div>



            </div>
        </div>

        <!-- DETAILS & PRICING UNIFIED FORM -->
        <form action="{{ route('instructor.course.update', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- COURSE DETAILS TAB -->
            <div x-show="activeTab === 'details'" x-transition style="display: none;" class="pb-12">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 max-w-5xl">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">Basic Information</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Course Title</label>
                            <input type="text" name="title" value="{{ old('title', $course->title) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition bg-gray-50">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                <select name="category_id" x-model="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition bg-white">
                                    <option value="">Select a subject...</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Level</label>
                                <select name="level" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition bg-white capitalize">
                                    <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Student Capacity</label>
                                <input type="number" name="max_students" value="{{ old('max_students', $course->max_students) }}" placeholder="e.g. 100 (Leave blank for unlimited)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition bg-gray-50">
                                <p class="text-xs text-gray-500 mt-2">Leave blank or set to empty for unlimited seats.</p>
                            </div>
                            <div x-show="!isFree">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Course Price (৳/BDT)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-2 text-gray-500">৳</span>
                                    <input type="number" step="1" name="price" x-model="price" 
                                           class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition @error('price') border-red-500 bg-red-50 @else bg-gray-50 @enderror">
                                </div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2">
                                    Min: ৳<span x-text="number_format(minLimit)"></span> | Max: ৳<span x-text="number_format(maxLimit)"></span> | Enter 0 for Free
                                </p>
                                @error('price')
                                    <p class="text-xs text-red-600 mt-1 font-bold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition bg-gray-50">{{ old('description', $course->description) }}</textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Requirements</label>
                            <textarea name="requirements" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition bg-gray-50">{{ old('requirements', $course->requirements) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">What Students Will Learn</label>
                            <textarea name="what_you_will_learn" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition bg-gray-50">{{ old('what_you_will_learn', is_array($course->what_you_will_learn) ? implode("\n", $course->what_you_will_learn) : $course->what_you_will_learn) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Course Thumbnail</label>
                            @if($course->cover_image)
                                <div class="mb-4">
                                    <img src="{{ asset('storage/' . $course->cover_image) }}" class="h-32 rounded-lg shadow-sm border border-gray-100 object-cover">
                                </div>
                            @endif
                            <input type="file" name="cover_image" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none transition file:mr-4 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>

            <!-- PRICING TAB -->
            <div x-show="activeTab === 'pricing'" x-transition style="display: none;" class="pb-12">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 max-w-5xl">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">Pricing Options</h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <div>
                                <p class="font-bold text-gray-900">Free Course</p>
                                <p class="text-sm text-gray-500">Make this course available for free</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" x-model="isFree" name="is_free_flag" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#5A4BFF]"></div>
                            </label>
                        </div>



                        <div x-show="!isFree">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Discount Price (Optional)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-2 text-gray-500">৳</span>
                                <input type="number" step="1" name="discount_price" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition bg-gray-50">
                            </div>
                        </div>

                        <div x-show="!isFree" class="bg-[#F0F5FF] border border-blue-200 rounded-lg p-6 mt-6">
                            <h3 class="text-sm font-bold text-blue-900 mb-4">Revenue Calculator</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between text-blue-800">
                                    <span>List Price:</span>
                                    <span x-text="'৳' + price"></span>
                                </div>
                                <div class="flex justify-between text-blue-800">
                                    <span>Platform Fee (30%):</span>
                                    <span x-text="'-৳' + fee"></span>
                                </div>
                                <div class="flex justify-between font-bold text-emerald-600 pt-3 border-t border-blue-200 text-lg">
                                    <span>Your Revenue:</span>
                                    <span x-text="'৳' + revenue"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Global Save Button for Details & Pricing -->
            <div x-show="activeTab === 'details' || activeTab === 'pricing'" style="display: none;" class="flex border-t border-gray-200 py-6 items-center flex-row-reverse mb-10 max-w-5xl">
                <button type="submit" class="px-8 py-3 bg-[#5A4BFF] text-white rounded-lg font-bold hover:bg-indigo-700 transition shadow">
                    @if($course->admin_feedback && !$course->is_submitted && !$course->is_published)
                        Save & Resubmit for Approval
                    @else
                        Save Changes
                    @endif
                </button>
            </div>
        </form>
    </div>

    <div id="moduleModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 id="moduleModalTitle" class="font-bold text-lg text-gray-900">Add New Module</h3>
                <button type="button" onclick="closeModuleModal()" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <form id="moduleForm" action="{{ route('instructor.module.store', $course->id) }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="_method" id="moduleMethod" value="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Module Title</label>
                    <input type="text" id="moduleTitleField" name="title" placeholder="e.g. Introduction to Web Development" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition">
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModuleModal()" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#5A4BFF] text-white rounded-lg font-medium hover:bg-indigo-700 transition">Save Module</button>
                </div>
            </form>
        </div>
    </div>

    <div id="lessonModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 id="lessonModalTitle" class="font-bold text-lg text-gray-900">Add New Lesson</h3>
                <button type="button" onclick="closeLessonModal()" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            
            <form id="lessonForm" method="POST" enctype="multipart/form-data" class="p-6" x-data="{ videoSource: 'url' }">
                @csrf
                <input type="hidden" name="_method" id="lessonMethod" value="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lesson Title</label>
                    <input type="text" name="title" placeholder="e.g. Setting up VS Code" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lesson Type</label>
                        <select name="type" id="lessonTypeDropdown" onchange="toggleDynamicFields()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition bg-white">
                            <option value="video">Video Lecture</option>
                            <option value="article">Article/Reading</option>
                            <option value="quiz">Quiz</option>
                            <option value="assignment">Assignment</option>
                            <option value="resource">Downloadable Resource</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                        <input type="text" name="duration" placeholder="e.g. 2:58:30, 45m, 2h 30m" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition">
                    </div>
                </div>
                
                <div id="videoUrlBlock" class="mb-4 block">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700">Video Source</label>
                        <div class="flex bg-gray-100 p-1 rounded-lg">
                            <button type="button" @click="videoSource = 'url'" :class="videoSource === 'url' ? 'bg-white shadow-sm' : ''" class="px-3 py-1 text-[10px] font-bold uppercase rounded-md transition">URL</button>
                            <button type="button" @click="videoSource = 'upload'" :class="videoSource === 'upload' ? 'bg-white shadow-sm' : ''" class="px-3 py-1 text-[10px] font-bold uppercase rounded-md transition">Upload</button>
                        </div>
                    </div>
                    
                    <div x-show="videoSource === 'url'">
                        <input type="url" name="video_url" placeholder="e.g. https://www.youtube.com/watch?v=..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition">
                    </div>
                    
                    <div x-show="videoSource === 'upload'" style="display: none;">
                        <input type="file" name="video_file" accept="video/mp4,video/x-m4v,video/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition file:mr-4 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                        <p class="text-[10px] text-gray-500 mt-2 font-medium">Max Size: 500MB (MP4 recommended)</p>
                    </div>
                </div>

                <div id="contentBlock" class="mb-4 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Article Content / Documentation / Instructions</label>
                    <textarea name="content" rows="4" placeholder="Write your article, assignment instructions, or paste documentation links here..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition"></textarea>
                </div>

                <div id="transcriptBlock" class="mb-4 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lesson Transcript / Notes (For AI Tutor)</label>
                    <textarea name="transcript" rows="4" placeholder="Paste video transcript or detailed notes here..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition"></textarea>
                    <p class="text-[10px] text-gray-500 mt-2 font-medium">Paste the video transcript or detailed notes here. The AI Assistant will use this to answer student questions.</p>
                </div>

                <div id="quizBlock" class="mb-4 hidden pt-4 border-t border-gray-100">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Time Limit (Minutes)</label>
                            <input type="number" name="time_limit_minutes" placeholder="e.g. 30 (Leave blank for infinite)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Passing Target (%)</label>
                            <input type="number" name="passing_percent" placeholder="e.g. 80" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition">
                        </div>
                    </div>
                </div>

                <div id="assignmentBlock" class="mb-4 hidden pt-4 border-t border-gray-100">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Total Marks</label>
                            <input type="number" name="total_marks" placeholder="e.g. 100" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Passing Marks</label>
                            <input type="number" name="passing_marks" placeholder="e.g. 50" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition">
                        </div>
                    </div>
                </div>

                <div id="resourceBlock" class="mb-4 border-t border-gray-100 pt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Attached Resource (Optional)</label>
                    <input type="file" name="resource_file" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A4BFF] outline-none transition file:mr-4 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="text-xs text-gray-500 mt-1">Upload PDF, ZIP, code files, etc (Max: 20MB)</p>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeLessonModal()" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#5A4BFF] text-white rounded-lg font-medium hover:bg-indigo-700 transition">Save Lesson</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal Toggles
        function openModuleModal() { 
            document.getElementById('moduleModalTitle').innerText = 'Add New Module';
            document.getElementById('moduleForm').action = `/instructor/course/{{ $course->id }}/module`;
            document.getElementById('moduleMethod').value = 'POST';
            document.getElementById('moduleTitleField').value = '';
            document.getElementById('moduleModal').classList.remove('hidden'); 
        }
        function openEditModuleModal(moduleId, title) {
            document.getElementById('moduleModalTitle').innerText = 'Edit Module';
            document.getElementById('moduleForm').action = `/instructor/course/{{ $course->id }}/module/${moduleId}`;
            document.getElementById('moduleMethod').value = 'PUT';
            document.getElementById('moduleTitleField').value = title;
            document.getElementById('moduleModal').classList.remove('hidden'); 
        }
        function closeModuleModal() { document.getElementById('moduleModal').classList.add('hidden'); }

        function openLessonModal(moduleId) {
            document.getElementById('lessonModalTitle').innerText = 'Add New Lesson';
            let form = document.getElementById('lessonForm');
            form.action = `/instructor/course/{{ $course->id }}/module/${moduleId}/lesson`;
            document.getElementById('lessonMethod').value = 'POST';
            
            document.querySelector('#lessonForm input[name="title"]').value = '';
            document.getElementById('lessonTypeDropdown').value = 'video';
            document.querySelector('#lessonForm input[name="duration"]').value = '';
            document.querySelector('#lessonForm input[name="video_url"]').value = '';
            document.querySelector('#lessonForm textarea[name="content"]').value = '';
            document.querySelector('#lessonForm textarea[name="transcript"]').value = '';

            // Access Alpine state
            let alpineData = Alpine.$data(document.getElementById('lessonForm'));
            alpineData.videoSource = 'url';

            document.getElementById('lessonModal').classList.remove('hidden');
            toggleDynamicFields();
        }

        function openEditLessonModal(moduleId, lessonId, title, type, duration, videoUrl, content, videoPath, transcript) {
            document.getElementById('lessonModalTitle').innerText = 'Edit Lesson';
            let form = document.getElementById('lessonForm');
            form.action = `/instructor/course/{{ $course->id }}/module/${moduleId}/lesson/${lessonId}`;
            document.getElementById('lessonMethod').value = 'PUT';
            
            document.querySelector('#lessonForm input[name="title"]').value = title;
            document.getElementById('lessonTypeDropdown').value = type;
            document.querySelector('#lessonForm input[name="duration"]').value = duration;
            document.querySelector('#lessonForm input[name="video_url"]').value = videoUrl;
            document.querySelector('#lessonForm textarea[name="content"]').value = content;
            document.querySelector('#lessonForm textarea[name="transcript"]').value = transcript;

            // Access Alpine state
            let alpineData = Alpine.$data(document.getElementById('lessonForm'));
            if (videoPath && videoPath !== '') {
                alpineData.videoSource = 'upload';
            } else {
                alpineData.videoSource = 'url';
            }

            document.getElementById('lessonModal').classList.remove('hidden');
            toggleDynamicFields();
        }
        function closeLessonModal() { document.getElementById('lessonModal').classList.add('hidden'); }

        // Dynamic Form Logic
        function toggleDynamicFields() {
            let type = document.getElementById('lessonTypeDropdown').value;
            let videoBlock = document.getElementById('videoUrlBlock');
            let contentBlock = document.getElementById('contentBlock');
            let transcriptBlock = document.getElementById('transcriptBlock');
            let quizBlock = document.getElementById('quizBlock');
            let assignmentBlock = document.getElementById('assignmentBlock');

            // Hide everything first
            videoBlock.classList.add('hidden');
            contentBlock.classList.add('hidden');
            transcriptBlock.classList.add('hidden');
            if(quizBlock) quizBlock.classList.add('hidden');
            if(assignmentBlock) assignmentBlock.classList.add('hidden');

            if (type === 'video') {
                videoBlock.classList.remove('hidden');
                transcriptBlock.classList.remove('hidden');
            } else if (type === 'quiz') {
                contentBlock.classList.remove('hidden'); // For Quiz Instructions
                if(quizBlock) quizBlock.classList.remove('hidden');
            } else if (type === 'assignment') {
                contentBlock.classList.remove('hidden'); // For Assignment Instructions
                if(assignmentBlock) assignmentBlock.classList.remove('hidden');
            } else {
                // Article or Resource
                contentBlock.classList.remove('hidden');
                transcriptBlock.classList.remove('hidden');
            }
        }
    </script>


    <script>
        function number_format(number) {
            return new Intl.NumberFormat().format(number);
        }
    </script>
@endsection
