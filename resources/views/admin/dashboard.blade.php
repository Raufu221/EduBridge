@extends('layouts.admin')

@section('title', 'Platform Overview')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Welcome Banner -->
        <div class="bg-[#5A4BFF] rounded-2xl p-8 text-white shadow-xl relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-2xl font-black mb-1 text-white tracking-tight">Welcome back, {{ Auth::user()->name }} 👋</h1>
                <p class="text-indigo-100 text-sm opacity-90">Here's what's happening on your platform today.</p>
            </div>
            <!-- Decorative circle -->
            <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-white opacity-10 blur-2xl pointer-events-none"></div>
        </div>

        <!-- Metric Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            
            <!-- Revenue Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between group hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-500">Platform Earnings</h3>
                    <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div>
                    <p class="text-2xl font-black text-gray-900 tracking-tight">৳{{ number_format($platformRevenue, 0) }}</p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Platform Revenue</p>
                </div>
            </div>

            <!-- Published Courses Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between group hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-500">Live Courses</h3>
                    <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-500 group-hover:bg-indigo-500 group-hover:text-white transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>
                <div>
                    <p class="text-2xl font-black text-gray-900 tracking-tight">{{ $totalPublishedCourses }}</p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Live Courses</p>
                </div>
            </div>

            <!-- Pending Approvals Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between group hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-500">Pending Approvals</h3>
                    <div class="w-8 h-8 rounded-full bg-rose-50 flex items-center justify-center text-rose-500 group-hover:bg-rose-500 group-hover:text-white transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>
                <div>
                    <p class="text-2xl font-black text-rose-600 tracking-tight">{{ $totalPendingCourses }}</p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Needs Approval</p>
                </div>
            </div>

            <!-- Total Users Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between group hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-500">Active Users</h3>
                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-2xl font-black text-gray-900 tracking-tight">{{ $totalStudents + $totalInstructors }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Active Users</p>
                    </div>
                    <div class="text-right flex flex-col items-end">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1"><span class="text-indigo-600">{{ $totalInstructors }}</span> Instr</p>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none"><span class="text-indigo-600">{{ $totalStudents }}</span> Stud</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
