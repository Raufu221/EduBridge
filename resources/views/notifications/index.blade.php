<x-app-layout>
    <div class="min-h-screen bg-cream py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10">
                <h1 class="text-3xl font-bold text-charcoal">Notification History</h1>
                <p class="text-muted-foreground font-medium mt-1">Stay updated with your latest course activities.</p>
            </div>

            <div class="bg-warm-white rounded-3xl border border-border shadow-sm overflow-hidden">
                <div class="divide-y divide-border/50">
                    @forelse($notifications as $notification)
                        <div class="p-6 transition hover:bg-cream/50 {{ $notification->read_at ? 'opacity-75' : 'bg-white' }}">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $notification->read_at ? 'bg-gray-100 text-gray-400' : 'bg-terracotta/10 text-terracotta' }}">
                                        @if(($notification->data['type'] ?? '') === 'announcement')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-charcoal leading-snug">
                                            {{ $notification->data['message'] ?? 'New Notification' }}
                                        </p>
                                        <p class="text-[11px] font-bold text-muted uppercase tracking-wider mt-1">
                                            {{ $notification->data['course_title'] ?? 'General' }} • {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                
                                @if(!$notification->read_at)
                                    <a href="{{ route('notifications.read', $notification->id) }}" class="px-4 py-2 bg-terracotta text-white text-[11px] font-black uppercase tracking-widest rounded-lg shadow-lg shadow-terracotta/20 hover:opacity-90 transition">
                                        View & Read
                                    </a>
                                @else
                                    <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Seen</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-20 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <p class="text-muted font-bold uppercase tracking-widest">No notifications yet</p>
                        </div>
                    @endforelse
                </div>

                @if($notifications->hasPages())
                    <div class="p-6 bg-gray-50/50 border-t border-border/50">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
