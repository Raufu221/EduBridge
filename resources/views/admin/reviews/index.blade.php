@extends('layouts.admin')

@section('title', 'Content Moderation: Reviews')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Stats Header -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total feedback</p>
                <div class="flex items-center gap-4">
                    <span class="text-4xl font-black text-gray-900">{{ \App\Models\Review::count() }}</span>
                    <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-1 rounded-lg uppercase tracking-wider">Entries</span>
                </div>
            </div>
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-2">Critical Reviews</p>
                <div class="flex items-center gap-4">
                    <span class="text-4xl font-black text-amber-600">{{ \App\Models\Review::where('rating', '<', 3)->count() }}</span>
                    <span class="bg-amber-50 text-amber-600 text-[10px] font-bold px-2 py-1 rounded-lg uppercase tracking-wider">Low Rated</span>
                </div>
            </div>
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm border-l-4 border-l-red-500">
                <p class="text-[10px] font-black text-red-500 uppercase tracking-widest mb-2">Hidden Content</p>
                <div class="flex items-center gap-4">
                    <span class="text-4xl font-black text-red-600">{{ \App\Models\Review::where('is_hidden', true)->count() }}</span>
                    <span class="bg-red-50 text-red-600 text-[10px] font-bold px-2 py-1 rounded-lg uppercase tracking-wider">Moderated</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 tracking-tight">System Moderation</h2>
                        <p class="text-sm font-medium text-gray-500 mt-1">Review student comments and manage public visibility.</p>
                    </div>
                    
                    <form action="{{ route('admin.reviews.index') }}" method="GET" class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search reviews..." 
                               class="rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 min-w-[250px]">
                        <button type="submit" class="bg-gray-900 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-black transition">Search</button>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Student</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Course & Instructor</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Comment</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Status</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-right">Moderation</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($reviews as $review)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-500 border border-gray-200">
                                            {{ substr($review->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $review->user->name }}</p>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $review->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-sm font-bold text-indigo-600 line-clamp-1 italic">"{{ $review->course->title }}"</p>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">By {{ $review->course->instructor->name }}</p>
                                </td>
                                <td class="px-8 py-6 min-w-[300px]">
                                    <div class="flex text-amber-400 mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3 h-3" fill="{{ $i <= $review->rating ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        @endfor
                                    </div>
                                    <p class="text-sm text-gray-600 italic">"{{ Str::limit($review->comment, 80) }}"</p>
                                </td>
                                <td class="px-8 py-6">
                                    @if($review->is_hidden)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-red-100 text-red-600 uppercase tracking-widest border border-red-200">HIDDEN</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-600 uppercase tracking-widest border border-emerald-200">VISIBLE</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2 text-left">
                                        <form action="{{ route('admin.reviews.toggle', $review->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2 {{ $review->is_hidden ? 'text-emerald-500 hover:bg-emerald-50' : 'text-amber-500 hover:bg-amber-50' }} rounded-lg transition" title="{{ $review->is_hidden ? 'Unhide' : 'Hide' }}">
                                                @if($review->is_hidden)
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                @else
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 14.122l4.242-4.242m-2.424 2.424L9 10.122m3.657 3.535L11.25 16.033m5.657-5.657l-2.033 2.033"></path></svg>
                                                @endif
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.reviews.delete', $review->id) }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to PERMANENTLY delete this review? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-400 hover:bg-red-50 hover:text-red-600 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center text-gray-500 font-medium">No reviews found matching your criteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-8 py-5 bg-gray-50/50 border-t border-gray-100">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
