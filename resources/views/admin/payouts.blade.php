@extends('layouts.admin')

@section('title', 'Payout Management')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Payout Management</h1>
            <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mt-1 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                Review and process instructor withdrawal requests.
            </p>
        </div>
        <div class="bg-white px-3 py-1.5 rounded-xl shadow-sm border border-gray-100 shrink-0">
            <span class="text-[9px] font-black text-indigo-600 uppercase tracking-widest tracking-tighter">Finance Hub</span>
        </div>
    </div>

    <!-- 1. Pending Requests (The Queue) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/30">
            <h3 class="text-lg font-black text-gray-900 tracking-tight">Pending Approval ({{ $pendingRequests->count() }})</h3>
            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1">Requests that need processing.</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Instructor</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Method</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Account Details</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pendingRequests as $payout)
                        <tr class="hover:bg-gray-50/30 transition">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-black text-[10px]">
                                        {{ substr($payout->instructor->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 text-xs">{{ $payout->instructor->name }}</p>
                                        <p class="text-[10px] text-gray-400">{{ $payout->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-sm font-black text-gray-900">৳{{ number_format($payout->amount, 0) }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="bg-amber-50 text-amber-600 px-2 py-1 rounded-lg border border-amber-100 text-[9px] font-black uppercase tracking-widest">
                                    {{ $payout->payout_method }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-[11px] font-medium text-gray-600 max-w-xs break-words">
                                    {{ $payout->account_details }}
                                </p>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <button type="button" 
                                        onclick="openProcessingModal('{{ $payout->id }}', '{{ $payout->instructor->name }}', '{{ number_format($payout->amount, 0) }}', '{{ $payout->payout_method }}', '{{ addslashes($payout->account_details) }}')"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-[9px] font-black uppercase tracking-widest px-4 py-2 rounded-xl transition shadow-md shadow-indigo-100">
                                    Process Payment
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="p-4 bg-gray-50 rounded-full text-gray-300">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">All caught up! No pending requests.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. Payout Archives -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden opacity-80">
        <div class="px-8 py-6 border-b border-gray-100">
            <h3 class="text-lg font-black text-gray-900 tracking-tight">Recent Activity</h3>
            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1">History of processed payouts.</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Instructor</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">TrxID</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($completedRequests as $payout)
                        <tr>
                            <td class="px-8 py-5 text-xs text-gray-500 font-medium">
                                {{ $payout->processed_at ? $payout->processed_at->format('M d, Y') : $payout->updated_at->format('M d, Y') }}
                            </td>
                            <td class="px-8 py-5 text-xs font-bold text-gray-800">
                                {{ $payout->instructor->name }}
                            </td>
                            <td class="px-8 py-5 text-xs font-black text-gray-900 text-sm">
                                ৳{{ number_format($payout->amount, 0) }}
                            </td>
                            <td class="px-8 py-5 text-[10px] font-black text-indigo-500 uppercase tracking-tight">
                                {{ $payout->payout_trx_id ?? 'N/A' }}
                            </td>
                            <td class="px-8 py-5">
                                <span class="{{ $payout->status === 'paid' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }} px-2 py-1 rounded-lg border text-[8px] font-black uppercase tracking-widest">
                                    {{ $payout->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($completedRequests->hasPages())
            <div class="px-8 py-6 border-t border-gray-100">
                {{ $completedRequests->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Process Payout Modal -->
<div id="processModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeProcessingModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-indigo-600 px-8 py-8 relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-xl font-black text-white tracking-tight">Process Payout</h3>
                    <p class="text-indigo-100 text-[10px] font-black uppercase tracking-widest mt-1">Audit Trail & Proof of Payment</p>
                </div>
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
            </div>

            <form id="processingForm" method="POST" class="p-8">
                @csrf
                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Payable Amount</p>
                                <p class="text-lg font-black text-gray-900" id="modalAmount">৳0</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Method</p>
                                <p class="text-xs font-black text-indigo-600 uppercase" id="modalMethod">N/A</p>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Recipient Details</p>
                            <p class="text-xs font-bold text-gray-700 whitespace-pre-line" id="modalDetails"></p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Official Transaction ID (TrxID)</label>
                        <input type="text" name="payout_trx_id" required
                               class="w-full bg-gray-50 border-0 rounded-2xl py-4 px-4 text-gray-900 font-black focus:ring-2 focus:ring-indigo-500 transition"
                               placeholder="e.g. T20210411-92381">
                        <p class="mt-2 text-[9px] text-gray-400 font-medium italic">Required for financial audit and instructor receipt.</p>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="closeProcessingModal()"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 text-[10px] font-black uppercase tracking-widest py-4 rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest py-4 rounded-xl transition shadow-lg shadow-indigo-100">
                        Confirm & Mark Paid
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openProcessingModal(id, name, amount, method, details) {
    const modal = document.getElementById('processModal');
    const form = document.getElementById('processingForm');
    
    // Set form action dynamically
    form.action = `/admin/payouts/${id}/paid`;
    
    // Set display text
    document.getElementById('modalAmount').innerText = '৳' + amount;
    document.getElementById('modalMethod').innerText = method;
    document.getElementById('modalDetails').innerText = details;
    
    modal.classList.remove('hidden');
}

function closeProcessingModal() {
    document.getElementById('processModal').classList.add('hidden');
}
</script>
@endsection
