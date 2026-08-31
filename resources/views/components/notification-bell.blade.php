<div class="relative ms-3" x-data="{ open: false }">
    <button @click="open = !open" 
            class="relative p-2 text-gray-400 hover:text-indigo-600 focus:outline-none transition duration-150 ease-in-out">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        
        @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
        @if($unreadCount > 0)
            <span class="absolute top-1.5 right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white shadow-sm border-2 border-white">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown Content -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl py-2 z-50 border border-gray-100 dark:border-gray-700 overflow-hidden"
         style="display: none;">
        
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50">
            <span class="text-sm font-black text-gray-900 dark:text-gray-100 uppercase tracking-tight">Notifications</span>
            <span class="text-[10px] uppercase font-black text-indigo-600 tracking-widest bg-indigo-50 px-2 py-0.5 rounded-full">Recent</span>
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                <a href="javascript:void(0)" 
                   onclick="markAsRead('{{ $notification->id }}', this)"
                   class="block px-4 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out border-b border-gray-50 dark:border-gray-700 last:border-0 relative">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-1">{{ $notification->data['sender_name'] ?? 'System' }}</span>
                        <p class="text-xs font-bold text-gray-900 dark:text-gray-100 leading-tight">
                            {{ $notification->data['title'] ?? 'New Announcement' }}
                        </p>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 line-clamp-2 leading-normal">
                            {{ $notification->data['message'] ?? '' }}
                        </p>
                        <span class="text-[9px] text-gray-400 mt-2 flex items-center gap-1 uppercase font-bold tracking-tighter">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="px-4 py-12 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100 shadow-inner">
                        <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">You are all caught up!</p>
                </div>
            @endforelse
        </div>

        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50">
            <a href="{{ route('notifications.index') }}" class="text-[10px] block text-center text-indigo-600 hover:text-indigo-700 font-black uppercase tracking-widest transition">
                View All Notification History
            </a>
        </div>
    </div>
</div>

<script>
    function markAsRead(id, element) {
        fetch(`/notifications/${id}/read`)
            .then(response => {
                if (response.ok) {
                    // Small micro-interaction: fade out and remove
                    element.style.opacity = '0.5';
                    element.style.pointerEvents = 'none';
                    
                    // Optional: refresh unread count badge via JS if needed
                    // For now, simple redirect or UI update
                    window.location.reload(); 
                }
            })
            .catch(error => console.error('Error marking as read:', error));
    }
</script>
