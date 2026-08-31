@extends('layouts.admin')

@section('title', 'Transactions')

@section('content')
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Transactions</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Verify pending payments and monitor financial history.</p>
        </div>
        
        <div class="flex gap-2">
            <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest {{ !request('status') ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">All Transactions</a>
            <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest {{ request('status') == 'pending' ? 'bg-amber-500 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">Pending Approvals</a>
            <a href="{{ route('admin.payments.index', ['status' => 'completed']) }}" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest {{ request('status') == 'completed' ? 'bg-emerald-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">Completed</a>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-8">
        @if($transactions->isEmpty())
            <div class="p-24 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">No transactions match your search</p>
            </div>
        @else
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Student & Course</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Amount (Net Paid)</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Method & Reference</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                        
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($transactions as $trx)
                        <tr class="hover:bg-gray-50/50 transition duration-150">
                            <td class="px-8 py-5">
                                <p class="font-bold text-gray-900 text-sm">{{ $trx->user->name }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase truncate max-w-[250px]" title="{{ $trx->course->title }}">{{ $trx->course->title }}</p>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <p class="font-black text-gray-900 text-sm">৳{{ number_format($trx->net_paid) }}</p>
                                <p class="text-[10px] text-indigo-500 font-bold uppercase">Incl. Fee: ৳{{ number_format($trx->commission_amount) }}</p>
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-tighter {{ $trx->payment_method == 'stripe' ? 'bg-indigo-100 text-indigo-700' : 'bg-red-100 text-red-700' }}">
                                    {{ str_replace('_', ' ', $trx->payment_method) }}
                                </span>
                                @if($trx->manual_trx_id)
                                    <p class="text-[10px] font-mono font-black text-gray-600 mt-1 uppercase">ID: {{ $trx->manual_trx_id }}</p>
                                @else
                                    <p class="text-[10px] font-mono text-gray-400 mt-1 uppercase">{{ Str::limit($trx->gateway_ref, 20) }}</p>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-center">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-amber-100 text-amber-700',
                                        'completed' => 'bg-emerald-100 text-emerald-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        'refunded' => 'bg-gray-100 text-gray-700',
                                    ];
                                @endphp
                                <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest {{ $statusClasses[$trx->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $trx->status }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($trx->status === 'pending')
                                        <form action="{{ route('admin.payments.approve', $trx->id) }}" method="POST" onsubmit="return confirm('Ensure the bKash/Nagad TrxID matches your mobile records. Approve?')">
                                            @csrf
                                            <button type="submit" class="w-10 h-10 flex items-center justify-center bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-100 transition border border-emerald-100 shadow-sm" title="Approve Transaction">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.payments.reject', $trx->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition border border-red-100 shadow-sm" title="Reject Transaction">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </form>
                                    @endif

                                    
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mt-6">
        {{ $transactions->links() }}
    </div>
@endsection
