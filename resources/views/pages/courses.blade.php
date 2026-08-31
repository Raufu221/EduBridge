@extends('layouts.public')

@section('title', 'Course Catalog | EduBridge')

@section('content')
<div class="bg-slate-50 min-h-screen">
    
    <!-- Top Header Section (Floating Container) -->
    <div class="max-w-[1400px] mx-auto px-6 lg:px-10 pt-8 pb-2">
        <div class="bg-white rounded-[2rem] p-8 lg:p-12 shadow-sm border border-gray-100 relative overflow-hidden">
            <!-- Subtle modern brand color glow accent in background -->
            <div class="absolute -top-16 -right-16 w-60 h-60 bg-orange-500/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-16 -left-16 w-60 h-60 bg-amber-500/5 rounded-full blur-3xl"></div>
            
            <h1 class="text-4xl lg:text-5xl font-serif font-black text-stone-900 tracking-tight mb-4 relative z-10">
                Explore the Repository
            </h1>
            <p class="text-lg text-stone-500 font-medium max-w-2xl leading-relaxed relative z-10">
                Discover curated academies led by the world's most innovative minds. Skip the noise, master the craft.
            </p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="max-w-[1400px] mx-auto px-6 lg:px-10 py-8 lg:py-10">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12 items-start">
            
            <!-- 1. The Filter Sidebar (col-span-1) -->
            <div class="lg:col-span-1 sticky top-24">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-lg font-bold text-stone-900">Filters</h2>
                        @if(request()->hasAny(['search', 'category', 'price', 'sort']))
                            <a href="{{ route('courses.index') }}" class="text-xs font-bold text-orange-600 hover:text-orange-700 transition">Clear All</a>
                        @endif
                    </div>

                    <form action="{{ route('courses.index') }}" method="GET" class="space-y-8">
                        
                        <!-- Search Bar -->
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-stone-400 uppercase tracking-widest ml-1">Search Academy</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Keywords..." 
                                       class="w-full bg-gray-100 border-none rounded-xl py-3.5 px-5 text-sm font-bold text-stone-900 focus:ring-2 focus:ring-orange-600/20 transition placeholder:text-stone-400">
                            </div>
                        </div>

                        <!-- Discipline Dropdown -->
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-stone-400 uppercase tracking-widest ml-1">Discipline</label>
                            <select name="category" class="w-full bg-gray-100 border-none rounded-xl py-3.5 px-5 text-sm font-bold text-stone-900 focus:ring-2 focus:ring-orange-600/20 transition cursor-pointer appearance-none">
                                <option value="all">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Enrollment Type (Custom Pill Radios) -->
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-stone-400 uppercase tracking-widest ml-1">Enrollment Type</label>
                            <div class="flex flex-col gap-2">
                                @foreach(['all' => 'Any Investment', 'free' => 'Free Entry', 'paid' => 'Premium Pass'] as $val => $label)
                                <label class="relative flex items-center justify-between p-4 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition group">
                                    <span class="text-sm font-bold text-stone-700 group-hover:text-stone-900">{{ $label }}</span>
                                    <input type="radio" name="price" value="{{ $val }}" {{ request('price', 'all') === $val ? 'checked' : '' }} class="form-radio text-orange-600 focus:ring-orange-600 border-gray-300">
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Ordering -->
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-stone-400 uppercase tracking-widest ml-1">Ordering</label>
                            <select name="sort" class="w-full bg-gray-100 border-none rounded-xl py-3.5 px-5 text-sm font-bold text-stone-900 focus:ring-2 focus:ring-orange-600/20 transition cursor-pointer appearance-none">
                                <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full py-4 bg-orange-600 text-white rounded-xl font-black text-sm uppercase tracking-widest shadow-lg shadow-orange-600/20 hover:bg-orange-700 hover:shadow-orange-600/30 transition-all transform active:scale-95">
                            Update Results
                        </button>
                    </form>
                </div>
            </div>

            <!-- 2. The Course Grid (col-span-3) -->
            <div class="lg:col-span-3">
                <!-- Top Status Bar -->
                <div class="flex items-center justify-between mb-10">
                    <div class="bg-gray-200 text-gray-700 text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest">
                        {{ $courses->total() }} ACADEMIES FOUND
                    </div>
                </div>

                <!-- Card Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($courses as $course)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col h-full group transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                        <!-- Thumbnail -->
                        <a href="{{ route('courses.show', $course) }}" class="relative aspect-video w-full overflow-hidden block bg-gray-50">
                            @if($course->cover_image)
                                <img src="{{ asset('storage/' . $course->cover_image) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                            @else
                                <img src="https://images.unsplash.com/photo-1516321497487-e288fb19713f?auto=format&fit=crop&w=800&q=80" alt="Fallback" class="w-full h-full object-cover grayscale opacity-50">
                            @endif
                            <div class="absolute inset-0 bg-stone-900/0 group-hover:bg-stone-900/5 transition-colors"></div>
                        </a>
                        
                        <!-- Content -->
                        <div class="p-6 flex flex-col flex-1">
                            <!-- Category Badge -->
                            <div class="mb-4">
                                <span class="bg-blue-50 text-blue-700 text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-widest">
                                    {{ $course->category->name ?? 'General' }}
                                </span>
                            </div>

                            <!-- Title -->
                            <a href="{{ route('courses.show', $course) }}" class="text-lg font-bold text-stone-900 mb-6 block hover:text-orange-600 transition-colors line-clamp-2 leading-tight">
                                {{ $course->title }}
                            </a>

                            <!-- Instructor & Bottom Row -->
                            <div class="mt-auto space-y-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-stone-100 flex items-center justify-center overflow-hidden border border-gray-100">
                                        @if($course->instructor->profile_pic)
                                            <img src="{{ asset('storage/' . $course->instructor->profile_pic) }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-[10px] font-black text-stone-400">{{ substr($course->instructor->name ?? 'I', 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <span class="text-sm font-bold text-stone-500">{{ $course->instructor->name ?? 'Expert Mentor' }}</span>
                                </div>

                                <div class="flex items-center justify-between pt-5 border-t border-gray-50">
                                    <div class="flex items-center gap-1 text-orange-600">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        <span class="text-xs font-black text-stone-900">{{ $course->average_rating ?? '4.8' }}</span>
                                    </div>
                                    @if($course->price == 0)
                                        <span class="text-xl font-black text-orange-600">Free</span>
                                    @else
                                        <span class="text-xl font-black text-stone-900">৳{{ number_format($course->price, 0) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-stone-900 mb-2">No matching academies</h3>
                        <p class="text-stone-500 font-medium mb-8">Try adjusting your filters to find what you're looking for.</p>
                        <a href="{{ route('courses.index') }}" class="inline-flex px-8 py-4 bg-stone-900 text-white rounded-xl font-black text-sm uppercase tracking-widest hover:bg-black transition">Reset Search</a>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($courses->hasPages())
                <div class="mt-16 flex justify-center">
                    {{ $courses->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
