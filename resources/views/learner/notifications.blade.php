<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Notifications</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Stay updated with your courses and progress.</p>
                </div>
                @if($notifications->count() > 0)
                <form action="{{ route('notifications.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-gray-400 hover:text-red-500 transition uppercase tracking-widest flex items-center gap-2">
                        Clear All
                    </button>
                </form>
                @endif
            </div>

            {{-- Notifications List --}}
            <div class="space-y-4">
                @forelse($notifications as $notification)
                    <div class="relative">
                        <a href="{{ route('notifications.read', $notification->id) }}" 
                           class="block bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 hover:border-orange-500/50 hover:shadow-lg hover:shadow-orange-500/5 transition-all group">
                            
                            {{-- Unread Dot --}}
                            @if($notification->unread())
                                <div class="absolute top-6 right-6 w-2.5 h-2.5 bg-orange-500 rounded-full shadow-sm z-10"></div>
                            @endif

                            <div class="flex gap-5">
                                {{-- Icon --}}
                                <div class="w-12 h-12 rounded-2xl bg-orange-50/50 dark:bg-orange-500/10 text-orange-500 flex items-center justify-center shrink-0 border border-orange-100 dark:border-orange-500/20 group-hover:bg-orange-500 group-hover:text-white transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>

                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                    
                                    <h3 class="text-base text-gray-900 dark:text-gray-100 leading-snug mt-1 font-bold group-hover:text-orange-500 transition-colors">
                                        {!! $notification->data['message'] ?? 'New system alert' !!}
                                    </h3>

                                    {{-- Announcement Body / Extra Info --}}
                                    @if(isset($notification->data['description']) || isset($notification->data['body']))
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 line-clamp-2">
                                            {{ $notification->data['description'] ?? $notification->data['body'] }}
                                        </p>
                                    @endif
                                    
                                    <div class="inline-flex items-center gap-1 text-[10px] font-black text-orange-500 mt-4 uppercase tracking-widest opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all">
                                        Open Details
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="py-20 text-center bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-3xl">
                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-700/50 text-gray-300 dark:text-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">All caught up!</h3>
                        <p class="text-sm text-gray-500 mt-1">New notifications will appear here.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
