@extends('layouts.instructor')

@section('title', 'Start Your New Course')

@section('content')
    <div class="py-12" x-data="{ 
        category_id: '{{ old('category_id', '') }}',
        categories: {{ $categories->toJson() }},
        get activeCategory() {
            return this.categories.find(c => c.id == this.category_id);
        },
        get minLimit() {
            return this.activeCategory ? this.activeCategory.min_price : 500;
        },
        get maxLimit() {
            return this.activeCategory ? this.activeCategory.max_price : 15000;
        }
    }">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Let's Create a New Course</h1>

            <form action="{{ route('instructor.course.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="bg-white rounded-lg shadow-md p-8 border border-gray-100 mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">1. Basic Course Details</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Course Title</label>
                            <input type="text" name="title" placeholder="e.g. Master the Complete Web Stack" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Custom URL (Slug)</label>
                            <input type="text" name="slug" placeholder="e.g. web-dev-mastery" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 transition bg-gray-50 text-gray-600">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <select name="category_id" x-model="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 transition bg-white">
                                <option value="">Select a subject...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Level</label>
                            <select name="level" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 transition bg-white capitalize">
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-8 border border-gray-100 mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">2. Description & Media</h2>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                        <textarea name="description" rows="5" placeholder="Explain what students will achieve in this course..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 transition"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Course Cover Image</label>
                        <input type="file" name="cover_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                        <p class="text-xs text-gray-500 mt-2">Recommended size: 1280 x 720px (PNG, JPG, max 5MB)</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-8 border border-gray-100 mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">3. Professional Details</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">What students will learn</label>
                            <textarea name="what_you_will_learn" rows="4" placeholder='e.g. ["Build modern apps", "Master Laravel Eloquent", "Deploy to production"]' class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 transition bg-gray-50 text-gray-700 font-mono text-xs"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prerequisites/Requirements</label>
                            <textarea name="requirements" rows="4" placeholder="e.g. Basic understanding of PHP and databases..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 transition"></textarea>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Audience</label>
                        <input type="text" name="target_audience" placeholder="e.g. Beginner PHP developers, anyone interested in full stack development" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 transition">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Course Price (৳)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-2.5 text-gray-500">৳</span>
                                <input type="number" step="1" name="price" placeholder="1000" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 transition @error('price') border-red-500 bg-red-50 @enderror">
                            </div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2">
                                Min: ৳<span x-text="number_format(minLimit)"></span> | Max: ৳<span x-text="number_format(maxLimit)"></span> | Enter 0 for Free
                            </p>
                            @error('price')
                                <p class="text-xs text-red-600 mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Student Capacity</label>
                            <input type="number" name="max_students" placeholder="e.g. 100" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 transition">
                            <p class="text-xs text-gray-500 mt-2">Leave blank for unlimited seats.</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-100 mb-12">
                    <button type="submit" class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-indigo-700 transition shadow-md">
                        Save Course Draft & Continue to Builder
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function number_format(number) {
            return new Intl.NumberFormat().format(number);
        }
    </script>
@endsection
