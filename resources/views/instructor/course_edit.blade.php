@extends('layouts.instructor')

@section('title', 'Edit Course Settings')

@section('content')
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900">Course Settings</h1>
                <a href="{{ route('instructor.courses.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Courses
                </a>
            </div>

            <form action="{{ route('instructor.course.update', $course->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="bg-white rounded-lg shadow-md p-8 border border-gray-100 mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">1. Basic Course Details</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Course Title</label>
                            <input type="text" name="title" value="{{ old('title', $course->title) }}" placeholder="e.g. Master the Complete Web Stack" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Custom URL (Slug)</label>
                            <input type="text" name="slug" value="{{ old('slug', $course->slug) }}" placeholder="e.g. web-dev-mastery" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 transition bg-gray-50 text-gray-600">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 transition bg-white">
                                <option value="">Select a subject...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $course->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Level</label>
                            <select name="level" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 transition bg-white capitalize">
                                <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-8 border border-gray-100 mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">2. Description & Media</h2>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                        <textarea name="description" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 transition">{{ old('description', $course->description) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Course Cover Image</label>
                        
                        @if($course->cover_image)
                            <div class="mb-4">
                                <p class="text-xs text-gray-500 mb-2">Current Image:</p>
                                <img src="{{ asset('storage/' . $course->cover_image) }}" class="h-32 rounded-lg shadow-sm border border-gray-100 object-cover">
                            </div>
                        @endif

                        <input type="file" name="cover_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                        <p class="text-xs text-gray-500 mt-2">Recommended size: 1280 x 720px (PNG, JPG, max 5MB). Leave blank to keep current image.</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-8 border border-gray-100 mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">3. Professional Details</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">What students will learn (JSON)</label>
                            <textarea name="what_you_will_learn" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 transition bg-gray-50 text-gray-700 font-mono text-xs">{{ old('what_you_will_learn', is_array($course->what_you_will_learn) ? json_encode($course->what_you_will_learn) : $course->what_you_will_learn) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prerequisites/Requirements</label>
                            <textarea name="requirements" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 transition">{{ old('requirements', $course->requirements) }}</textarea>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Audience</label>
                        <input type="text" name="target_audience" value="{{ old('target_audience', $course->target_audience) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 transition">
                    </div>

                    <div class="w-1/2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Course Price (৳)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-2.5 text-gray-500">৳</span>
                            <input type="number" step="1" name="price" value="{{ old('price', $course->price) }}" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 transition">
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Leave blank or set to 0 for a free course.</p>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-100 mb-12">
                    <button type="submit" class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-indigo-700 transition shadow-md">
                        Update Course Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
