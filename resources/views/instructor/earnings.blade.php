@extends('layouts.instructor')

@section('title', 'Financial Mastery Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Dashboard Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Earnings Hub</h1>
                <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mt-1 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                    Managing your revenue intelligence and splits.
                </p>
            </div>
            <div class="bg-white px-3 py-1.5 rounded-xl shadow-sm border border-gray-100 shrink-0">
                <span class="text-[9px] font-black text-rose-600 uppercase tracking-widest ">Financial Mastery</span>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold uppercase tracking-wider flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-bold uppercase tracking-wider flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-bold uppercase tracking-wider">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>Please correct the errors below:</span>
                </div>
                <ul class="list-disc pl-5 space-y-1 text-[11px] font-medium text-rose-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- 1. KPI Ribbon (3 Cards) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Lifetime Earnings -->
            <div class="relative bg-white p-6 rounded-2xl shadow-sm border border-gray-50 overflow-hidden group hover:shadow-md transition-all duration-300">
                <div class="relative z-10 flex items-center gap-5">
                    <div class="p-3 bg-rose-100 text-rose-600 rounded-2xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Lifetime Earnings</p>
                        <h3 class="text-xl font-black text-gray-900 leading-tight">৳{{ number_format($totalEarnings, 0) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Available Balance -->
            <div class="relative bg-white p-6 rounded-2xl shadow-sm border border-gray-50 overflow-hidden group hover:shadow-md transition-all duration-300 ring-2 ring-indigo-500/10">
                <div class="relative z-10 flex items-center justify-between gap-5">
                    <div class="flex items-center gap-5">
                        <div class="p-3 bg-indigo-100 text-indigo-600 rounded-2xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Available for Payout</p>
                            <h3 class="text-xl font-black text-indigo-600 leading-tight">৳{{ number_format($availableBalance, 0) }}</h3>
                        </div>
                    </div>
                    
                    @if($availableBalance >= 500 && !$hasPendingRequest)
                        <button onclick="document.getElementById('payoutModal').classList.remove('hidden')" 
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-wider px-4 py-2 rounded-xl transition shadow-lg shadow-indigo-200">
                            Withdraw
                        </button>
                    @elseif($hasPendingRequest)
                        <span class="bg-amber-50 text-amber-600 text-[8px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg border border-amber-100">
                            Request Pending
                        </span>
                    @else
                        <span class="text-[8px] font-black text-gray-300 uppercase tracking-widest">
                            Min 500৳ needed
                        </span>
                    @endif
                </div>
            </div>

            <!-- Earnings This Month -->
            <div class="relative bg-white p-6 rounded-2xl shadow-sm border border-gray-50 overflow-hidden group hover:shadow-md transition-all duration-300">
                <div class="relative z-10 flex items-center gap-5">
                    <div class="p-3 bg-emerald-100 text-emerald-600 rounded-2xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Earnings ({{ now()->format('M') }})</p>
                        <h3 class="text-xl font-black text-emerald-600 leading-tight">৳{{ number_format($earningsThisMonth, 0) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Revenue Trend Bar Chart -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-50 hover:shadow-md transition">
                <h3 class="text-lg font-black text-gray-900 tracking-tight mb-1">Revenue Trend</h3>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-8">Historical financial growth (last 6 months)</p>
                <div class="h-[300px]">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Payout History Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-50 overflow-hidden hover:shadow-md transition flex flex-col">
                <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-black text-gray-900 tracking-tight">Payout History</h3>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1">Status of your withdrawals</p>
                    </div>
                </div>
                
                <div class="overflow-y-auto max-h-[300px]">
                    <table class="w-full text-left">
                        <thead class="sticky top-0 bg-white z-10">
                            <tr class="bg-gray-50/80 backdrop-blur-sm">
                                <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                                <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">Method</th>
                                <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Amount</th>
                                <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Receipt / TrxID</th>
                                <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($payoutRequests as $request)
                                <tr class="hover:bg-gray-50/30 transition">
                                    <td class="px-8 py-4 text-[11px] font-bold text-gray-600">
                                        {{ $request->processed_at ? $request->processed_at->format('M d, Y') : $request->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-8 py-4 text-[10px] uppercase font-black text-gray-400">
                                        {{ $request->payout_method }}
                                    </td>
                                    <td class="px-8 py-4 text-right font-black text-gray-900 text-xs">
                                        ৳{{ number_format($request->amount, 0) }}
                                    </td>
                                    <td class="px-8 py-4 text-center">
                                        @if($request->payout_trx_id)
                                            <span class="text-[9px] font-black text-indigo-500 bg-indigo-50 px-2 py-1 rounded-lg border border-indigo-100 tracking-tight">
                                                {{ $request->payout_trx_id }}
                                            </span>
                                        @else
                                            <span class="text-[9px] font-bold text-gray-300 uppercase tracking-widest italic">Wait...</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-4 text-center">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                'processing' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                'paid' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                'rejected' => 'bg-rose-50 text-rose-600 border-rose-100'
                                            ];
                                        @endphp
                                        <span class="{{ $statusColors[$request->status] ?? 'bg-gray-50' }} px-2.5 py-1 rounded-lg border text-[8px] font-black uppercase tracking-widest">
                                            {{ $request->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-10 text-center">
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">No payout history found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 3. Revenue Ledger Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-50 overflow-hidden hover:shadow-md transition">
            <div class="px-8 py-6 border-b border-gray-50">
                <h3 class="text-lg font-black text-gray-900 tracking-tight">Financial Statistics</h3>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1">Transparency on platform splits (30/70)</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Course Name</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Sales</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Gross (৳)</th>
                            <th class="px-8 py-4 text-[10px] font-black text-indigo-400 uppercase tracking-widest text-right">Fee (30%)</th>
                            <th class="px-8 py-4 text-[10px] font-black text-rose-500 uppercase tracking-widest text-right">Earning (70%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($courseLedger as $row)
                            <tr class="hover:bg-gray-50/30 transition">
                                <td class="px-8 py-5">
                                    <p class="font-bold text-gray-900 text-xs tracking-tight">{{ $row->course->title }}</p>
                                </td>
                                <td class="px-8 py-5 text-center text-xs font-black text-gray-600">
                                    {{ $row->sales_count }}
                                </td>
                                <td class="px-8 py-5 text-right font-black text-gray-900 text-xs">
                                    ৳{{ number_format($row->total_gross, 0) }}
                                </td>
                                <td class="px-8 py-5 text-right font-bold text-gray-400 text-[10px] italic">
                                    -৳{{ number_format($row->platform_fee, 0) }}
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <span class="bg-rose-50 px-3 py-1.5 rounded-xl font-black text-rose-600 text-xs">
                                        ৳{{ number_format($row->instructor_earning, 0) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Request Payout Modal -->
<div id="payoutModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('payoutModal').classList.add('hidden')"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-indigo-600 px-8 py-10 relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-2xl font-black text-white tracking-tight">Withdraw Funds</h3>
                    <p class="text-indigo-100 text-[10px] font-black uppercase tracking-widest mt-2">Request your earnings to be transferred.</p>
                </div>
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
            </div>

            <form action="{{ route('instructor.payout.store') }}" method="POST" class="p-8" x-data="{ method: 'bkash' }">
                @csrf
                <div class="space-y-6">
                    <!-- Amount Input -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Amount (Min 500৳)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">৳</span>
                            <input type="number" name="amount" min="500" max="{{ $availableBalance }}" step="0.01" required
                                   class="w-full bg-gray-50 border-0 rounded-2xl py-4 pl-8 pr-4 text-gray-900 font-black focus:ring-2 focus:ring-indigo-500 transition"
                                   placeholder="0.00">
                        </div>
                        <p class="mt-2 text-[9px] font-bold text-indigo-500 uppercase tracking-widest text-right">Available: ৳{{ number_format($availableBalance, 2) }}</p>
                    </div>

                    <!-- Payout Method -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Recipient Method</label>
                        <select name="payout_method" x-model="method" required class="w-full bg-gray-50 border-0 rounded-2xl py-4 px-4 text-gray-900 font-bold focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="bkash">bKash (Wallet Transfer)</option>
                            <option value="nagad">Nagad (Wallet Transfer)</option>
                            <option value="bank">Bank Transfer (EFT/Wire)</option>
                        </select>
                    </div>

                    <!-- Dynamic Account Details -->
                    <div class="space-y-4">
                        <!-- Mobile Banking Fields -->
                        <div x-show="method === 'bkash' || method === 'nagad'" x-transition>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1" x-text="method.charAt(0).toUpperCase() + method.slice(1) + ' Number'"></label>
                            <input type="text" name="mobile_number" 
                                   pattern="^[0-9]{11}$"
                                   maxlength="11"
                                   placeholder="e.g., 017XXXXXXXX"
                                   :required="method === 'bkash' || method === 'nagad'"
                                   class="w-full bg-gray-50 border-0 rounded-2xl py-4 px-4 text-gray-900 font-bold focus:ring-2 focus:ring-indigo-500 transition"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <p class="mt-1 text-[8px] text-gray-400 font-bold uppercase tracking-widest">Must be exactly 11 digits</p>
                        </div>

                        <!-- Bank Transfer Fields -->
                        <div x-show="method === 'bank'" x-transition class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Bank Name & Branch</label>
                                <input type="text" name="bank_name" 
                                       placeholder="e.g., Dutch-Bangla Bank, Dhanmondi Branch"
                                       :required="method === 'bank'"
                                       class="w-full bg-gray-50 border-0 rounded-2xl py-4 px-4 text-gray-900 font-bold focus:ring-2 focus:ring-indigo-500 transition">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Account Number</label>
                                <input type="text" name="account_number" 
                                       pattern="[0-9]{13}"
                                       maxlength="13"
                                       placeholder="Enter your 13-digit account number"
                                       :required="method === 'bank'"
                                       class="w-full bg-gray-50 border-0 rounded-2xl py-4 px-4 text-gray-900 font-bold focus:ring-2 focus:ring-indigo-500 transition"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <p class="mt-1 text-[8px] text-gray-400 font-bold uppercase tracking-widest">Must be exactly 13 digits</p>
                            </div>
                        </div>

                        <!-- Hidden legacy textarea for backend compatibility if needed, or we can just send the combined data -->
                        <input type="hidden" name="account_details" 
                               :value="method === 'bank' ? 'Bank: ' + document.getElementsByName('bank_name')[0]?.value + ' | A/C: ' + document.getElementsByName('account_number')[0]?.value : method.toUpperCase() + ': ' + document.getElementsByName('mobile_number')[0]?.value">
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <button type="button" onclick="document.getElementById('payoutModal').classList.add('hidden')"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 text-[10px] font-black uppercase tracking-widest py-4 rounded-2xl transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest py-4 rounded-2xl transition shadow-lg shadow-indigo-100">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const revCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: 'Revenue (৳)',
                data: {!! json_encode($earningsData) !!},
                backgroundColor: '#F43F5E', // Rose/Coral
                borderRadius: 20,
                barThickness: 40,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#F8F9FA', drawBorder: false }, ticks: { font: { weight: 'bold' }, callback: (v) => '৳' + v } },
                x: { grid: { display: false }, ticks: { font: { weight: 'bold' } } }
            }
        }
    });
});
</script>
@endsection
