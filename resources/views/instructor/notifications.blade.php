@extends('layouts.instructor')
@section('title', 'Notifications Center')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900">Notifications</h1>
            <p class="text-sm text-gray-500 mt-1">Manage all your course activity and platform alerts.</p>
        </div>
        @if($notifications->count() > 0)
        <form action="{{ route('notifications.clear') }}" method="POST">
            @csrf
            <button type="submit" class="text-xs font-bold text-gray-400 hover:text-red-500 transition uppercase tracking-widest flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Clear All
            </button>
        </form>
        @endif
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        @forelse($notifications as $notification)
            <div class="group p-6 border-b border-gray-50 hover:bg-gray-50 transition relative">
                <div class="flex gap-5">
                    {{-- Icon based on type --}}
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>

                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ $notification->created_at->diffForHumans() }}</span>
                            @if($notification->unread())
                                <span class="w-2 h-2 bg-terracotta rounded-full shadow-sm shadow-terracotta/40"></span>
                            @endif
                        </div>
                        <h3 class="text-base text-gray-900 leading-tight">
                            {!! $notification->data['message'] ?? 'New system alert' !!}
                        </h3>
                        @if(isset($notification->data['link']))
                        <a href="{{ route('notifications.read', $notification->id) }}" class="inline-flex items-center gap-1 text-xs font-bold text-terracotta mt-3 hover:gap-2 transition-all">
                            View details
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="py-24 text-center">
                <div class="w-20 h-20 bg-gray-50 text-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">All caught up!</h3>
                <p class="text-sm text-gray-500 mt-1">When you get new notifications, they'll appear here.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
