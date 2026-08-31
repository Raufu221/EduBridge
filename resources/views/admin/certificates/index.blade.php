@extends('layouts.admin')

@section('title', 'Certificate Moderation')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Certificate Moderation</h1>
            <p class="text-sm text-gray-500 mt-1">Audit, verify, and manage all issued academic credentials.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-4">
            <form action="{{ route('admin.certificates.index') }}" method="GET" class="flex items-center gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-auto">
                    <svg class="w-4 h-4 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search code, student, or course..." class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none w-full sm:w-80 bg-white shadow-sm">
                </div>
                @if(request()->has('search'))
                    <a href="{{ route('admin.certificates.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 shrink-0">Clear</a>
                @endif
                <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm hover:bg-black transition">Search</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-emerald-800 rounded-lg bg-emerald-50 border border-emerald-200 font-bold">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100 uppercase text-[10px] tracking-widest">
                <tr>
                    <th class="px-6 py-4">Credential</th>
                    <th class="px-6 py-4">Learner</th>
                    <th class="px-6 py-4">Course</th>
                    <th class="px-6 py-4">Academic Score</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($certificates as $cert)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="font-black text-gray-900 font-mono text-xs uppercase">{{ $cert->certificate_code }}</div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase mt-0.5">{{ $cert->issue_date->format('M d, Y') }}</p>
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-800">
                        {{ $cert->full_name }}
                        <p class="text-[11px] text-gray-400 font-medium lowercase">{{ $cert->user->email }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-gray-900 line-clamp-1">{{ $cert->course->title }}</p>
                        <p class="text-[10px] text-indigo-500 font-bold uppercase mt-1">{{ $cert->course->instructor->name }}</p>
                    </td>
                    <td class="px-6 py-4 font-black text-gray-900">
                        {{ round($cert->average_score, 1) }}%
                    </td>
                    <td class="px-6 py-4">
                        @if($cert->is_valid)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-[10px] font-black uppercase tracking-wider border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-50 text-red-700 rounded-full text-[10px] font-black uppercase tracking-wider border border-red-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Revoked
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end items-center gap-2">
                            <a href="{{ route('certificate.verify', $cert->certificate_code) }}" target="_blank" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Verify Hub">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m15-11v2a1 1 0 001 1h2m-6 0h.01M7 16h10M7 20h10"></path></svg>
                            </a>
                            <form action="{{ route('admin.certificates.toggle', $cert->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" 
                                        onclick="return confirm('Change validity of certificate {{ $cert->certificate_code }}?')" 
                                        class="p-2 {{ $cert->is_valid ? 'text-red-400 hover:text-red-600 hover:bg-red-50' : 'text-emerald-400 hover:text-emerald-600 hover:bg-emerald-50' }} rounded-lg transition"
                                        title="{{ $cert->is_valid ? 'Revoke Certificate' : 'Restore Certificate' }}">
                                    @if($cert->is_valid)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @endif
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($certificates->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $certificates->links() }}
            </div>
        @endif
        
        @if($certificates->isEmpty())
            <div class="px-6 py-20 text-center flex flex-col items-center">
                <div class="w-16 h-16 bg-gray-50 text-gray-200 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">No matching credentials found.</p>
            </div>
        @endif
    </div>
</div>
@endsection
