@extends('layouts.admin')

@section('title', 'Instructor Applications')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ 
    modalOpen: false, 
    rejectionModalOpen: false,
    selectedApp: null,
    openModal(app) {
        this.selectedApp = app;
        this.modalOpen = true;
    },
    openRejectionModal(app) {
        this.selectedApp = app;
        this.rejectionModalOpen = true;
    }
}">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Instructor Applications</h1>
            <p class="text-sm text-gray-500 mt-1">Review, approve, or reject incoming requests to teach on the platform.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-200 shadow-sm flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-100 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Applicant</th>
                    <th class="px-6 py-4 w-1/3">Course Pitch</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Date Submitted</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($applications as $app)
                <tr class="hover:bg-gray-50 transition group {{ $app->status === 'pending' ? 'bg-white' : 'bg-gray-50 opacity-75' }}">
                    <!-- User Info (Handles Guests correctly) -->
                    <td class="px-6 py-5 align-top">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-700 font-bold border border-indigo-100">
                                {{ substr($app->full_name ?? $app->user->name ?? 'G', 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $app->full_name ?? $app->user->name ?? 'Guest Applicant' }}</p>
                                <p class="text-xs text-gray-500">{{ $app->email ?? $app->user->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-2 mt-2">
                            @if($app->user_id)
                                <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">Member</span>
                            @else
                                <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-[10px] font-bold uppercase">Guest</span>
                            @endif
                        </div>
                    </td>

                    <!-- Pitch -->
                    <td class="px-6 py-5 align-top">
                        <p class="text-[10px] font-black uppercase text-indigo-400 tracking-widest mb-1">{{ $app->proposal_topic ?? 'No Title' }}</p>
                        <div class="text-gray-700 text-sm leading-relaxed line-clamp-2">
                            {{ $app->teaching_approach }}
                        </div>
                    </td>

                    <!-- Status -->
                    <td class="px-6 py-5 align-top">
                        @if($app->status === 'pending')
                            <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold uppercase tracking-wider inline-flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending</span>
                        @elseif($app->status === 'approved')
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold uppercase tracking-wider">Approved</span>
                        @else
                            <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-bold uppercase tracking-wider">Rejected</span>
                        @endif
                    </td>

                    <td class="px-6 py-5 align-top font-medium text-gray-500">
                        {{ $app->created_at->diffForHumans() }}
                    </td>

                    <!-- Actions -->
                    <td class="px-6 py-5 align-top text-right space-y-2">
                        <div class="flex flex-col gap-2 items-end">
                            <button @click="openModal({{ $app->toJson() }})" class="inline-flex items-center px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-lg text-xs font-bold hover:bg-gray-50 transition shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                View Details
                            </button>

                            @if($app->status === 'pending')
                                <form action="{{ route('admin.applications.approve', $app->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Approve this request?');" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition shadow-sm w-full justify-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Approve
                                    </button>
                                </form>
                                
                                <button type="button" @click="openRejectionModal({{ $app->toJson() }})" class="inline-flex items-center px-4 py-2 bg-rose-50 text-rose-600 rounded-lg text-xs font-bold hover:bg-rose-100 transition border border-rose-100 w-full justify-center">
                                    Reject
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 text-gray-400 mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Queue is Empty</h3>
                        <p class="text-gray-500 max-w-sm mx-auto">There are currently no active applications to review.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $applications->links() }}
    </div>

    <!-- Rejection Reason Modal -->
    <template x-if="rejectionModalOpen">
        <div class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div @click="rejectionModalOpen = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 p-8">
                    <h3 class="text-xl font-black text-gray-900 mb-2">Rejection Reason</h3>
                    <p class="text-sm text-gray-500 mb-6">Please provide a constructive reason for rejecting <span class="font-bold text-gray-900" x-text="selectedApp.full_name || selectedApp.user.name"></span>. This will be sent to them via email.</p>
                    
                    <form :action="'/admin/applications/' + selectedApp.id + '/reject'" method="POST">
                        @csrf
                        <textarea name="reason" rows="4" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-rose-500 outline-none transition" placeholder="Explain what they can improve..."></textarea>
                        
                        <div class="flex justify-end gap-3 mt-8">
                            <button type="button" @click="rejectionModalOpen = false" class="px-6 py-2.5 bg-white text-gray-700 border border-gray-200 rounded-xl text-xs font-bold">Cancel</button>
                            <button type="submit" class="px-6 py-2.5 bg-rose-600 text-white rounded-xl text-xs font-bold hover:bg-rose-700 transition shadow-lg shadow-rose-200">Confirm Rejection</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    <!-- Application Details Modal (Alpine.js) -->
    <template x-if="modalOpen">
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div @click="modalOpen = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-100">
                    <div class="bg-white px-8 pt-8 pb-8">
                        <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-2xl font-black">
                                    <span x-text="selectedApp.full_name ? selectedApp.full_name.charAt(0) : (selectedApp.user ? selectedApp.user.name.charAt(0) : 'G')"></span>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-black text-gray-900" x-text="selectedApp.full_name || (selectedApp.user ? selectedApp.user.name : 'Guest Applicant')"></h3>
                                    <p class="text-sm text-gray-500" x-text="selectedApp.email || (selectedApp.user ? selectedApp.user.email : 'N/A')"></p>
                                </div>
                            </div>
                            <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <!-- Column 1: Identity & Credentials -->
                            <div class="space-y-8">
                                <div>
                                    <h4 class="text-[10px] font-black uppercase tracking-widest text-indigo-500 mb-4">Identity & Contact</h4>
                                    <div class="space-y-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg></div>
                                            <span class="text-sm font-bold text-gray-700" x-text="selectedApp.phone || 'Phone not provided'"></span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg></div>
                                            <template x-if="selectedApp.linkedin">
                                                <a :href="selectedApp.linkedin" target="_blank" class="text-sm font-bold text-blue-600 hover:underline">LinkedIn Profile</a>
                                            </template>
                                            <template x-if="!selectedApp.linkedin">
                                                <span class="text-sm font-medium text-gray-400 italic">No LinkedIn link</span>
                                            </template>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></div>
                                            <template x-if="selectedApp.portfolio || selectedApp.portfolio_url">
                                                <a :href="selectedApp.portfolio || selectedApp.portfolio_url" target="_blank" class="text-sm font-bold text-indigo-600 hover:underline">Portfolio / Website</a>
                                            </template>
                                            <template x-if="!(selectedApp.portfolio || selectedApp.portfolio_url)">
                                                <span class="text-sm font-medium text-gray-400 italic">No portfolio provided</span>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-[10px] font-black uppercase tracking-widest text-indigo-500 mb-4">Background</h4>
                                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Expertise Area</p>
                                                <p class="text-sm font-black text-gray-800 uppercase" x-text="selectedApp.expertise || 'N/A'"></p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Exp. Years</p>
                                                <p class="text-sm font-black text-gray-800" x-text="(selectedApp.experience_years || '0') + ' Years'"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-[10px] font-black uppercase tracking-widest text-indigo-500 mb-4">Demo Class Video</h4>
                                    <template x-if="selectedApp.demo_video_url">
                                        <a :href="selectedApp.demo_video_url" target="_blank" class="flex items-center gap-4 p-4 bg-red-50 text-red-700 rounded-2xl border border-red-100 hover:bg-red-100 transition group">
                                            <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition">
                                                <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-black uppercase tracking-widest">Watch Demo Session</p>
                                                <p class="text-[10px] text-red-600/70 font-bold" x-text="selectedApp.demo_video_url"></p>
                                            </div>
                                        </a>
                                    </template>
                                    <template x-if="!selectedApp.demo_video_url">
                                        <div class="p-6 bg-gray-50 border border-dashed border-gray-200 rounded-2xl text-center">
                                            <p class="text-xs text-gray-400 font-bold italic uppercase tracking-widest">No Demo Video Provided</p>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Column 2: Pitch & Proposal -->
                            <div class="space-y-8">
                                <div>
                                    <h4 class="text-[10px] font-black uppercase tracking-widest text-indigo-500 mb-4">Academy Proposal</h4>
                                    <div class="bg-indigo-50/50 rounded-2xl p-6 border border-indigo-100">
                                        <p class="text-[10px] uppercase font-bold text-indigo-400 mb-2">Proposed Course Title</p>
                                        <h5 class="text-xl font-black text-indigo-900 leading-tight mb-4" x-text="selectedApp.proposal_topic || 'No Title Provided'"></h5>
                                        <div class="h-px bg-indigo-100 mb-4"></div>
                                        <p class="text-[10px] uppercase font-bold text-indigo-400 mb-2">Teaching Approach / Pitch</p>
                                        <div class="text-sm text-gray-700 font-medium leading-relaxed whitespace-pre-wrap" x-text="selectedApp.teaching_approach"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-8 py-6 flex justify-end gap-3 border-t border-gray-100">
                        <button @click="modalOpen = false" class="px-6 py-2.5 bg-white text-gray-700 border border-gray-200 rounded-xl text-xs font-bold hover:bg-gray-50 transition shadow-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
