@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Global Announcements</h1>
        <p class="text-gray-500 mt-2">Broadcast a system-wide message to your community.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 animate-fade-in">
            <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Form Section --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                <form action="{{ route('admin.announcements.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Announcement Title</label>
                        <input type="text" name="title" required 
                               class="w-full rounded-2xl border-gray-100 bg-gray-50/50 focus:ring-indigo-500 focus:border-indigo-500 py-4 px-6 font-bold text-gray-700 transition-all"
                               placeholder="e.g., Scheduled Maintenance / New Feature Launch">
                        @error('title') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Target Audience</label>
                        <select name="target" required 
                                class="w-full rounded-2xl border-gray-100 bg-gray-50/50 focus:ring-indigo-500 focus:border-indigo-500 py-4 px-6 font-bold text-gray-700 transition-all appearance-none cursor-pointer">
                            <option value="all">All Users (Everyone)</option>
                            <option value="learner">Learners Only (Students)</option>
                            <option value="instructor">Instructors Only</option>
                        </select>
                        @error('target') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Broadcast Message</label>
                        <textarea name="message" rows="6" required 
                                  class="w-full rounded-2xl border-gray-100 bg-gray-50/50 focus:ring-indigo-500 focus:border-indigo-500 py-4 px-6 font-bold text-gray-700 transition-all resize-none"
                                  placeholder="Write your detailed announcement here..."></textarea>
                        @error('message') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 px-8 rounded-2xl shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                            Push Global Announcement
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- History Section --}}
        <div class="lg:col-span-1 space-y-6">
            <h3 class="text-xl font-black text-gray-900 tracking-tight">Announcement History</h3>
            
            <div class="space-y-4">
                @forelse($announcements as $ann)
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[10px] font-black uppercase tracking-widest {{ $ann->target_audience === 'all' ? 'text-indigo-500 bg-indigo-50' : ($ann->target_audience === 'learner' ? 'text-emerald-500 bg-emerald-50' : 'text-amber-500 bg-amber-50') }} px-2 py-0.5 rounded-full">
                                {{ $ann->target_audience ?? 'All' }}
                            </span>
                            <span class="text-[10px] font-bold text-gray-400">{{ $ann->created_at->format('M d, Y') }}</span>
                        </div>
                        <h4 class="font-bold text-gray-900 text-sm mb-1">{{ $ann->title }}</h4>
                        <p class="text-xs text-gray-500 line-clamp-3 leading-relaxed">{{ $ann->content }}</p>
                    </div>
                @empty
                    <div class="text-center py-12 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">No previous broadcasts</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
